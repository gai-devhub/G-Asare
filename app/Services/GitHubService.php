<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GitHubService
{
    /**
     * Fetch GitHub stats for a given username.
     * Caches the result to avoid hitting rate limits.
     *
     * @return array
     */
    public function getStats(): array
    {
        $username = config('services.github.username', env('GITHUB_USERNAME'));
        $token = config('services.github.token', env('GITHUB_TOKEN'));

        // Fallback default values
        $defaultStats = [
            'repositories' => '0',
            'commits' => '0',
            'collaborations' => '0',
            'lines_of_code' => '0',
        ];

        if (!$username || !$token) {
            Log::warning('GitHub username or token is missing in .env');
            return $defaultStats;
        }

        return Cache::remember('github_stats_' . $username . '_v3', now()->addMinutes(5), function () use ($username, $token, $defaultStats) {
            try {
                $query = '
                query($login: String!) {
                  user(login: $login) {
                    repositories(first: 100, ownerAffiliations: OWNER, isFork: false) {
                      totalCount
                      nodes {
                        languages(first: 10) {
                          totalSize
                        }
                      }
                    }
                    collaboratorRepos: repositories(first: 100, ownerAffiliations: [COLLABORATOR, ORGANIZATION_MEMBER]) {
                      totalCount
                    }
                    contributionsCollection {
                      restrictedContributionsCount
                      contributionCalendar {
                        totalContributions
                      }
                    }
                  }
                }';

                $response = Http::withToken($token)
                    ->post('https://api.github.com/graphql', [
                        'query' => $query,
                        'variables' => [
                            'login' => $username,
                        ],
                    ]);

                if ($response->successful()) {
                    $data = $response->json('data.user');

                    if (!$data) {
                        return $defaultStats;
                    }

                    $repos = env('GITHUB_OVERRIDE_REPOS', $data['repositories']['totalCount'] ?? 0);
                    $apiCollaborations = $data['collaboratorRepos']['totalCount'] ?? 0;
                    $collaborations = env('GITHUB_OVERRIDE_COLLABORATIONS', $apiCollaborations > 0 ? $apiCollaborations : 5);
                    
                    $totalBytes = collect($data['repositories']['nodes'] ?? [])->sum(function ($repo) {
                        return $repo['languages']['totalSize'] ?? 0;
                    });
                    // Estimate ~35 bytes per line of code on average
                    $loc = $totalBytes > 0 ? (int)($totalBytes / 35) : 0;
                    $formattedLoc = $loc;
                    if ($loc >= 1000) {
                        $formattedLoc = number_format($loc / 1000, 1) . 'k+';
                    }

                    $publicCommits = $data['contributionsCollection']['contributionCalendar']['totalContributions'] ?? 0;
                    $privateCommits = $data['contributionsCollection']['restrictedContributionsCount'] ?? 0;
                    $commits = $publicCommits + $privateCommits;

                    // Format commits if it's over 1000 (e.g., 1.2K)
                    $formattedCommits = $commits;
                    if ($commits >= 1000) {
                        $formattedCommits = number_format($commits / 1000, 1) . 'K+';
                    }

                    return [
                        'repositories' => $repos,
                        'commits' => $formattedCommits,
                        'collaborations' => $collaborations,
                        'lines_of_code' => $formattedLoc,
                    ];
                }

                Log::error('GitHub API failed: ' . $response->body());
                return $defaultStats;
                
            } catch (\Exception $e) {
                Log::error('GitHub API exception: ' . $e->getMessage());
                return $defaultStats;
            }
        });
    }
}
