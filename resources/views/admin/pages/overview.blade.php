@extends('admin.admin')

@section('title', 'Dashboard Overview')

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header" style="border-left: 4px solid var(--color-primary); padding-left: 1rem;">
        <h1 style="color: var(--color-primary); text-transform: uppercase; letter-spacing: 2px; text-shadow: 0 0 10px rgba(0,240,255,0.3);">System Overview</h1>
        <p style="color: var(--gray);">Welcome back, administrator. Here's your portfolio metrics.</p>
    </div>
    
    <!-- Stats Grid -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-folder-open"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $projectsCount }}</h3>
                <p>TOTAL PROJECTS</p>
                <span class="stat-meta">{{ $projectsDiffStr }}</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-code"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $skillsCount }}</h3>
                <p>ACTIVE SKILLS</p>
                <span class="stat-meta">Across {{ $skillsCategoriesCount }} categories</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $messagesCount }}</h3>
                <p>MESSAGES</p>
                <span class="stat-meta">Inquiries & Contact</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-blog"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $blogPostsCount }}</h3>
                <p>BLOG POSTS</p>
                <span class="stat-meta">Published Articles</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-route"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $journeyCount }}</h3>
                <p>JOURNEY ITEMS</p>
                <span class="stat-meta">Exp & Education</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon blue" style="color: #00f0ff; border-color: rgba(0, 240, 255, 0.3);">
                <i class="fas fa-images"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $galleryCount }}</h3>
                <p>GALLERY ITEMS</p>
                <span class="stat-meta">+{{ $filesStored }} files stored</span>
            </div>
        </div>
    </div>
    
    <div class="dashboard-row">
        <!-- Portfolio Stats Bar Chart -->
        <div class="chart-card flex-2">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-chart-bar" style="margin-right: 8px;"></i> Activity Metrics</div>
            </div>
            <div class="portfolio-stats-chart" style="height: 350px; padding: 10px; width: 100%;">
                <canvas id="portfolioChart"></canvas>
            </div>
        </div>
        
        <!-- Projects Pie Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-chart-pie" style="margin-right: 8px;"></i> Projects by Category</div>
            </div>
            <div class="portfolio-stats-chart" style="height: 350px; padding: 10px; display: flex; align-items: center; justify-content: center;">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="dashboard-row">
        <!-- Recent Activity -->
        <div class="chart-card flex-2">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-history" style="margin-right: 8px;"></i> System Logs</div>
                <a href="{{ route('admin.document-activity') }}" class="chart-link">View all logs</a>
            </div>
            
            <table class="data-table" data-search-table>
                <thead>
                    <tr>
                        <th style="color: var(--color-primary);">EVENT NAME</th>
                        <th style="color: var(--color-primary);">MODULE</th>
                        <th style="color: var(--color-primary);">STATUS</th>
                        <th style="color: var(--color-primary);">TIMESTAMP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivity as $activity)
                    <tr>
                        <td>{{ $activity->title }}</td>
                        <td><span class="activity-badge page-view">{{ $activity->type }}</span></td>
                        <td><span class="status published" style="background: rgba(0, 240, 255, 0.1); color: #00f0ff; border: 1px solid rgba(0, 240, 255, 0.3); box-shadow: 0 0 5px rgba(0, 240, 255, 0.2);">Logged</span></td>
                        <td style="color: var(--gray);">{{ $activity->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px; color: var(--gray);">No recent activity in system logs.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if(method_exists($recentActivity, 'hasPages') && $recentActivity->hasPages())
                <div class="pagination-wrapper">
                    {{ $recentActivity->links('admin.pagination') }}
                </div>
            @endif

            

            
        </div>
        
        <!-- Storage Usage -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-hdd" style="margin-right: 8px;"></i> Disk Usage</div>
            </div>
            <div class="storage-widget">
                <div class="storage-progress" style="margin: 20px 0;">
                    <div class="storage-circle" style="background: conic-gradient(var(--color-primary) {{ $storagePercent }}%, rgba(128,128,128,0.1) 0); border: 1px solid rgba(0,240,255,0.2); box-shadow: 0 0 15px rgba(0,240,255,0.1);">
                        <span class="storage-percent" style="color: var(--text); font-weight: 600;">{{ $storagePercent }}%</span>
                    </div>
                </div>
                <p class="storage-text" style="color: var(--color-primary);">{{ $storageMB }} MB of 1 GB allocated</p>
                <p class="storage-meta">{{ $filesStored }} Total uploaded files</p>
                <a href="{{ route('admin.my-files') }}" class="btn btn-outline" style="width: 100%; justify-content: center; margin-top: 10px;">ACCESS DRIVE</a>
            </div>
        </div>
    </div>
    
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.color = 'var(--text)';
    Chart.defaults.font.family = "'Poppins', sans-serif";

    // Bar Chart
    const barCtx = document.getElementById('portfolioChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [
                {
                    label: 'Projects',
                    data: {!! json_encode($projectData) !!},
                    backgroundColor: 'rgba(0, 240, 255, 0.8)',
                    borderColor: '#00f0ff',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.6
                },
                {
                    label: 'Skills',
                    data: {!! json_encode($skillData) !!},
                    backgroundColor: 'rgba(176, 38, 255, 0.8)',
                    borderColor: '#b026ff',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.6
                },
                {
                    label: 'Blog Posts',
                    data: {!! json_encode($blogData) !!},
                    backgroundColor: 'rgba(255, 0, 60, 0.8)',
                    borderColor: '#ff003c',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true, 
                    suggestedMax: 5,
                    ticks: { precision: 0 },
                    grid: { color: 'rgba(128, 128, 128, 0.1)' }
                },
                x: {
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { position: 'top', labels: { color: 'var(--text)' } },
                tooltip: { 
                    backgroundColor: 'rgba(10, 10, 15, 0.9)', 
                    titleColor: '#00f0ff', 
                    bodyColor: '#fff',
                    borderColor: 'rgba(0, 240, 255, 0.3)',
                    borderWidth: 1
                }
            }
        }
    });

    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($pieLabels) !!},
            datasets: [{
                data: {!! json_encode($pieData) !!},
                backgroundColor: [
                    'rgba(0, 240, 255, 0.8)',
                    'rgba(176, 38, 255, 0.8)',
                    'rgba(255, 0, 60, 0.8)',
                    'rgba(0, 255, 115, 0.8)',
                    'rgba(255, 174, 0, 0.8)'
                ],
                borderColor: 'var(--bg-surface)',
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { color: 'var(--text)', padding: 20 } },
                tooltip: { 
                    backgroundColor: 'rgba(10, 10, 15, 0.9)', 
                    titleColor: '#00f0ff', 
                    bodyColor: '#fff',
                    borderColor: 'rgba(0, 240, 255, 0.3)',
                    borderWidth: 1
                }
            }
        }
    });
});
</script>
@endpush
@endsection
