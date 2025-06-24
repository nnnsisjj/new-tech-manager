@extends('admin.layouts.app')

@section('title', 'Мои платежи')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Мои платежи</h1>

    @if($directionsWithPayments->count() > 0)
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Направление</th>
                        <th>Сумма</th>
                        <th>Способ оплаты</th>
                        <th>Статус</th>
                        <th>Остаток</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($directionsWithPayments as $directionData)
                        @php
                            $cumulativePaid = 0;
                        @endphp
                        
                        @foreach($directionData['payments'] as $payment)
                            @php
                                // Суммируем только завершенные платежи
                                if ($payment->status === 'completed') {
                                    $cumulativePaid += $payment->amount;
                                }
                                // Рассчитываем текущий остаток
                                $currentRemaining = max(0, $directionData['direction']->price - $cumulativePaid);
                            @endphp
                            <tr>
                                <td>{{ $payment->payment_date->format('d.m.Y') }}</td>
                                <td>{{ $directionData['direction']->name }}</td>
                                <td>{{ number_format($payment->amount, 2) }} ₽</td>
                                <td>{{ ucfirst($payment->payment_method) }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ $payment->status === 'completed' ? 'Оплачено' : 'В обработке' }}
                                    </span>
                                </td>
                                <td>
                                    @if($currentRemaining > 0)
                                        <span class="text-danger">{{ number_format($currentRemaining, 2) }} ₽</span>
                                    @else
                                        <span class="text-success">Оплачено полностью</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        
                        <!-- Итоговая строка по направлению -->
                        <tr class="bg-light">
                            <td colspan="2"><strong>Итого по направлению:</strong></td>
                            <td><strong>{{ number_format($directionData['paid'], 2) }} ₽</strong></td>
                            <td colspan="2"></td>
                            <td>
                                <strong>
                                    @if($directionData['remaining'] > 0)
                                        <span class="text-danger">{{ number_format($directionData['remaining'], 2) }} ₽</span>
                                    @else
                                        <span class="text-success">Оплачено полностью</span>
                                    @endif
                                </strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        У вас пока нет платежей.
    </div>
    @endif

    <a href="{{ route('student.payments.create') }}" class="btn btn-primary mt-3">
        Создать новый платеж
    </a>
</div>
@endsection