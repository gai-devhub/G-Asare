@extends('admin.admin')

@section('title', 'Dashboard Overview')

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header" >
        <h1 >System Overview</h1>
        <p >Welcome back, administrator. Here's your portfolio metrics.</p>
    </div>
    
    <!-- Stats Grid -->
    <div class="dashboard-grid" >
        <div class="stat-card" >
            <div class="stat-icon" >
                <i class="fas fa-folder"></i>
            </div>
            <div class="stat-info">
                <p >Total Projects</p>
                <h3 >{{ $projectsCount }}</h3>
                <span class="stat-meta" >{{ $projectsDiffStr }}</span>
            </div>
        </div>
        
        <div class="stat-card" >
            <div class="stat-icon" >
                <i class="fas fa-code"></i>
            </div>
            <div class="stat-info">
                <p >Active Skills</p>
                <h3 >{{ $skillsCount }}</h3>
                <span class="stat-meta" >Across {{ $skillsCategoriesCount }} categories</span>
            </div>
        </div>

        <div class="stat-card" >
            <div class="stat-icon" >
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-info">
                <p >Messages</p>
                <h3 >{{ $messagesCount }}</h3>
                <span class="stat-meta" >Inquiries & Contact</span>
            </div>
        </div>
        
        <div class="stat-card" >
            <div class="stat-icon" >
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <p >Blog Posts</p>
                <h3 >{{ $blogPostsCount }}</h3>
                <span class="stat-meta" >Published Articles</span>
            </div>
        </div>

        <div class="stat-card" >
            <div class="stat-icon" >
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="stat-info">
                <p >Journey Items</p>
                <h3 >{{ $journeyCount }}</h3>
                <span class="stat-meta" >Exp & Education</span>
            </div>
        </div>
        
        <div class="stat-card" >
            <div class="stat-icon" >
                <i class="fas fa-images"></i>
            </div>
            <div class="stat-info">
                <p >Gallery Items</p>
                <h3 >{{ $galleryCount }}</h3>
                <span class="stat-meta" >+{{ $filesStored }} files stored</span>
            </div>
        </div>
    </div>
    
    <div class="dashboard-row" >
        <!-- Portfolio Stats Bar Chart -->
        <div class="chart-card" >
            <div class="chart-header" >
                <div class="chart-title" >Activity Metrics</div>
            </div>
            <div class="portfolio-stats-chart" >
                <canvas id="portfolioChart"></canvas>
            </div>
        </div>
        
        <!-- Projects Pie Chart -->
        <div class="chart-card" >
            <div class="chart-header" >
                <div class="chart-title" >Projects by Category</div>
            </div>
            <div class="portfolio-stats-chart" >
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="dashboard-row" >
        <!-- Recent Activity -->
        <div class="chart-card" >
            <div class="chart-header" >
                <div class="chart-title" >System Logs</div>
                <a href="{{ route('admin.document-activity') }}" >View all</a>
            </div>
            
            @if($recentActivity->count() > 0)
            <div class="table-responsive">
            <table class="data-table" >
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Module</th>
                        <th>Owner</th>
                        <th>Time</th>
                        <th class="table-action-cell"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivity as $activity)
                    <tr>
                        <td>
                            <div class="table-name-cell">
                                <i class="fas fa-file-alt"></i>
                                <span>{{ $activity->title }}</span>
                            </div>
                        </td>
                        <td>{{ $activity->type }}</td>
                        <td>
                            <div class="table-owner-cell">
                                @php $profile = \App\Models\Profile::first(); @endphp
                                <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Owner">
                                <span>me</span>
                            </div>
                        </td>
                        <td>
                            <div class="table-location-cell">
                                <i class="fas fa-clock"></i>
                                <span>{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td class="table-action-cell">
                            <div class="kebab-menu-wrapper">
                                <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                                <ul class="kebab-dropdown">
                                    <li class="has-submenu">
                                        <button type="button"><i class="fas fa-info-circle"></i> File information <i class="fas fa-chevron-right"></i></button>
                                        <ul class="kebab-submenu kebab-submenu-left">
                                            <li><button type="button" onclick="openSidebar('details', { title: '{{ addslashes($activity->title) }}', category: 'Activity', type: 'Log', owner: 'System', modified: '{{ $activity->created_at ? $activity->created_at->format('M d, Y') : 'Unknown' }}', created: '{{ $activity->created_at ? $activity->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes($activity->message ?? 'No description') }}', imageUrl: '' })"><i class="fas fa-list"></i> Details</button></li>
                                            <li><button type="button" onclick="openSidebar('activity', { title: '{{ addslashes($activity->title) }}', category: 'Activity', type: 'Log', owner: 'System', modified: '{{ $activity->created_at ? $activity->created_at->format('M d, Y') : 'Unknown' }}', created: '{{ $activity->created_at ? $activity->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes($activity->message ?? 'No description') }}', imageUrl: '' })"><i class="fas fa-history"></i> Activity</button></li>
                                        </ul>
                                    </li>

                                    <li class="divider"></li>
                                    <li><a href="{{ route('admin.document-activity') }}"><i class="fas fa-eye"></i> View details</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @else
            <div class="empty-state-container">
                <div class="empty-state-illustration">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h2 class="empty-state-title">No recent activity</h2>
                <p class="empty-state-description">Your dashboard overview and recent system logs will appear here.</p>

            </div>
            @endif
            @if(method_exists($recentActivity, 'hasPages') && $recentActivity->hasPages())
                <div class="pagination-wrapper" >
                    {{ $recentActivity->links('admin.pagination') }}
                </div>
            @endif
        </div>
        
        <!-- Storage Usage -->
        <div class="chart-card" >
            <div class="chart-header" >
                <div class="chart-title" >Storage</div>
            </div>
            <div class="storage-widget" >
                <div class="storage-progress" style="--storage-deg: {{ $globalStoragePercent * 3.6 }}deg;">
                    <div class="storage-circle" >
                        <div >
                            <span >{{ $globalStoragePercent }}%</span>
                        </div>
                    </div>
                </div>
                <p >{{ $globalStorageMB }} MB used</p>
                <p >of 1 GB allocated</p>
                <a href="{{ route('admin.my-files') }}" class="btn" >Open Drive</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.color = '#888888'; // fallback solid color for canvas
    Chart.defaults.font.family = "'Inter', sans-serif";

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
                    backgroundColor: '#0F9D58',
                    borderColor: 'var(--color-primary)',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.6
                },
                {
                    label: 'Skills',
                    data: {!! json_encode($skillData) !!},
                    backgroundColor: '#4285F4',
                    borderColor: 'var(--color-accent)',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.6
                },
                {
                    label: 'Blog Posts',
                    data: {!! json_encode($blogData) !!},
                    backgroundColor: '#EA4335',
                    borderColor: 'var(--color-secondary)',
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
                    min: 0,
                    suggestedMax: 5,
                    ticks: { precision: 0 },
                    grid: { color: 'rgba(128, 128, 128, 0.1)' }
                },
                x: {
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { position: 'top', labels: { color: '#888888' } },
                tooltip: { 
                    backgroundColor: 'rgba(10, 10, 15, 0.9)', 
                    titleColor: 'var(--color-primary)', 
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
                    '#0F9D58',
                    '#4285F4',
                    '#EA4335',
                    '#FBBC05',
                    '#34A853'
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
                legend: { position: 'bottom', labels: { color: '#888888', padding: 20 } },
                tooltip: { 
                    backgroundColor: 'rgba(10, 10, 15, 0.9)', 
                    titleColor: 'var(--color-primary)', 
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




