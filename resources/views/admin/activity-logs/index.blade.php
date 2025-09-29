@extends('layouts.master')

@section('title', 'سجلات الأنشطة')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">سجلات الأنشطة</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">سجلات الأنشطة</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">سجلات الأنشطة</h3>
                            <div class="card-tools">
                                @if(auth()->user()->hasRole('super_admin'))
                                    <button type="button" class="btn btn-danger btn-sm" onclick="cleanupLogs()">
                                        <i class="fas fa-trash"></i> تنظيف السجلات القديمة
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="activity-logs-table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>المستخدم</th>
                                            <th>النموذج</th>
                                            <th>المعرف</th>
                                            <th>الإجراء</th>
                                            <th>الوصف</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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
$(document).ready(function() {
    $('#activity-logs-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.activity-logs.index") }}',
            type: 'GET'
        },
        columns: [
            { data: 'user_name', name: 'user_name' },
            { data: 'model_name', name: 'model_name' },
            { data: 'model_id', name: 'model_id' },
            { data: 'action', name: 'action', orderable: false },
            { data: 'description', name: 'description' },
            { data: 'date', name: 'created_at' }
        ],
        order: [[5, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
        }
    });
});

function cleanupLogs() {
    Swal.fire({
        title: 'تنظيف السجلات القديمة',
        input: 'number',
        inputLabel: 'عدد الأيام (السجلات الأقدم من هذا العدد سيتم حذفها)',
        inputValue: 30,
        showCancelButton: true,
        confirmButtonText: 'تنظيف',
        cancelButtonText: 'إلغاء',
        inputValidator: (value) => {
            if (!value || value <= 0) {
                return 'يرجى إدخال رقم صحيح أكبر من 0';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("admin.activity-logs.cleanup") }}',
                method: 'POST',
                data: {
                    days: result.value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('تم!', response.message, 'success');
                        $('#activity-logs-table').DataTable().ajax.reload();
                    }
                },
                error: function() {
                    Swal.fire('خطأ!', 'حدث خطأ أثناء تنظيف السجلات', 'error');
                }
            });
        }
    });
}
</script>
@endpush
