@extends('layouts.master')

@section('title', 'أنشطة المشرف: ' . $admin->name)

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">أنشطة المشرف: {{ $admin->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">الرئيسية</a></li>
                        <li class="breadcrumb-item active">أنشطة {{ $admin->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-info">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <h4 class="card-title text-white">هذا الشهر</h4>
                                    <h2 class="text-white">{{ $thisMonthCount }}</h2>
                                    <p class="text-white">عملية إنشاء</p>
                                </div>
                                <div class="col-4">
                                    <i class="fas fa-calendar-alt fa-3x text-white opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <h4 class="card-title text-white">طوال الفترة</h4>
                                    <h2 class="text-white">{{ $totalCount }}</h2>
                                    <p class="text-white">عملية إنشاء</p>
                                </div>
                                <div class="col-4">
                                    <i class="fas fa-chart-line fa-3x text-white opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Statistics -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 text-white"><i class="fas fa-calendar-alt"></i> إحصائيات هذا الشهر</h5>
                        </div>
                        <div class="card-body">
                            @if(!empty($thisMonthStats))
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>النموذج</th>
                                                <th class="text-center">العدد</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($thisMonthStats as $model => $count)
                                                <tr>
                                                    <td>{{ $model }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-primary">{{ $count }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center">لا توجد عمليات إنشاء هذا الشهر</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0 text-white"><i class="fas fa-chart-line"></i> اجمالي الاحصائيات</h5>
                        </div>
                        <div class="card-body">
                            @if(!empty($totalStats))
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>النموذج</th>
                                                <th class="text-center">العدد</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($totalStats as $model => $count)
                                                <tr>
                                                    <td>{{ $model }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success">{{ $count }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center">لا توجد عمليات إنشاء</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
let currentPeriod = 'all';
let table;

$(document).ready(function() {
    initializeDataTable();
});

function initializeDataTable() {
    if (table) {
        table.destroy();
    }

    table = $('#admin-activity-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.admins.activity", $admin->id) }}',
            type: 'GET',
            data: function(d) {
                d.period = currentPeriod;
            }
        },
        columns: [
            { data: 'model_name', name: 'model_name' },
            { data: 'model_id', name: 'model_id' },
            { data: 'created_data', name: 'created_data', orderable: false },
            { data: 'permission_check', name: 'permission_check', orderable: false },
            { data: 'date', name: 'created_at' }
        ],
        order: [[4, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
        },
        pageLength: 25,
        responsive: true
    });
}

function filterPeriod(period) {
    currentPeriod = period;

    // تحديث أزرار الفلترة
    $('.btn-group button').removeClass('btn-primary btn-outline-secondary').addClass('btn-outline-primary');
    if (period === 'month') {
        $('button[onclick="filterPeriod(\'month\')"]').removeClass('btn-outline-primary').addClass('btn-primary');
    } else {
        $('button[onclick="filterPeriod(\'all\')"]').removeClass('btn-outline-primary').addClass('btn-primary');
    }

    // إعادة تحميل الجدول
    table.ajax.reload();

    // تحديث الإحصائيات
    updateStatistics(period);
}

function updateStatistics(period) {
    const statisticsUrl = '{{ route("admin.admins.activity.statistics", $admin->id) }}?period=' + period;

    $.get(statisticsUrl)
        .done(function(response) {
            if (response.success) {
                updateStatisticsCards(response.statistics, period);
            }
        })
        .fail(function() {
            console.error('خطأ في تحديث الإحصائيات');
        });
}

function updateStatisticsCards(statistics, period) {
    const isMonth = period === 'month';
    const cardSelector = isMonth ? '.card-header.bg-primary' : '.card-header.bg-success';
    const cardBody = $(cardSelector).next('.card-body');

    if (Object.keys(statistics).length > 0) {
        let tableHtml = `
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>النموذج</th>
                            <th class="text-center">العدد</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        Object.entries(statistics).forEach(([model, count]) => {
            const badgeClass = isMonth ? 'bg-primary' : 'bg-success';
            tableHtml += `
                <tr>
                    <td>${model}</td>
                    <td class="text-center">
                        <span class="badge ${badgeClass}">${count}</span>
                    </td>
                </tr>
            `;
        });

        tableHtml += `
                    </tbody>
                </table>
            </div>
        `;

        cardBody.html(tableHtml);
    } else {
        const message = isMonth ? 'لا توجد عمليات إنشاء هذا الشهر' : 'لا توجد عمليات إنشاء';
        cardBody.html(`<p class="text-muted text-center">${message}</p>`);
    }
}

function exportData() {
    const exportUrl = '{{ route("admin.admins.activity.export", $admin->id) }}?period=' + currentPeriod;

    $.get(exportUrl)
        .done(function(response) {
            if (response.success) {
                // إنشاء وتحميل ملف JSON
                const dataStr = JSON.stringify(response, null, 2);
                const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

                const exportFileDefaultName = `admin_${response.admin_name}_activity_${response.period}_${new Date().toISOString().split('T')[0]}.json`;

                const linkElement = document.createElement('a');
                linkElement.setAttribute('href', dataUri);
                linkElement.setAttribute('download', exportFileDefaultName);
                linkElement.click();

                Swal.fire({
                    title: 'تم التصدير!',
                    text: `تم تصدير ${response.total_count} سجل بنجاح`,
                    icon: 'success',
                    timer: 2000
                });
            }
        })
        .fail(function() {
            Swal.fire('خطأ!', 'حدث خطأ أثناء تصدير البيانات', 'error');
        });
}
</script>
@endpush

@push('styles')
<style>
.opacity-75 {
    opacity: 0.75;
}

.card.bg-info .card-title,
.card.bg-success .card-title {
    font-weight: bold;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-left: 2px;
}

.table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

.badge {
    font-size: 0.8rem;
}
</style>
@endpush
