@extends('admin.layouts.app')

@section('title', 'Финансовый учет')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Финансовый учет</h1>
        <div>
            <a href="{{ route('admin.finance.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Экспорт платежей
            </a>
        </div>
    </div>

    <!-- Статистические карточки -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Общий доход</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalIncome ?? 0, 2) }} ₽
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Всего платежей</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $payments->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Таблица платежей -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Последние платежи</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Дата</th>
                            <th>Студент</th>
                            <th>Направление</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th>Способ оплаты</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('d.m.Y') }}</td>
                            <td>{{ $payment->student->full_name ?? 'Не указан' }}</td>
                            <td>{{ $payment->direction->name ?? 'Не указано' }}</td>
                            <td>{{ number_format($payment->amount, 2) }} ₽</td>
                            <td>
                                <form action="{{ route('admin.finance.update-status', $payment) }}" method="POST">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                        <option value="pending" {{ $payment->status === 'pending' ? 'selected' : '' }}>В обработке</option>
                                        <option value="completed" {{ $payment->status === 'completed' ? 'selected' : '' }}>Оплачено</option>
                                    </select>
                                </form>
                            </td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info" title="Детали">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Нет данных о платежах</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $payments->links() }}
            </div>
        </div>
    </div>

    <!-- Таблица долгов -->
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Долги студентов</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Студент</th>
                            <th>Направление</th>
                            <th>Стоимость направления</th>
                            <th>Оплачено</th>
                            <th>Долг</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studentsWithDebt as $item)
                            <tr>
                                <td>{{ $item['student']->full_name }}</td>
                                <td>{{ $item['direction'] }}</td>
                                <td>{{ number_format($item['direction_price'], 2) }} ₽</td>
                                <td>{{ number_format($item['paid'], 2) }} ₽</td>
                                <td class="text-danger">{{ number_format($item['debt'], 2) }} ₽</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Все студенты оплатили обучение</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection