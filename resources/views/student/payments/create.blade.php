@extends('admin.layouts.app')

@section('title', 'Создать платеж')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Создать платеж</h1>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('student.payments.store') }}" id="payment-form">
                @csrf
                
                <div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Направление *</label>
        <select name="direction_id" id="direction-select" class="form-select" required>
            <option value="">-- Выберите направление --</option>
            @foreach($directions as $direction)
                <option value="{{ $direction->id }}" 
                    data-price="{{ $direction->price }}"
                    @if($student->course && $student->course->direction_id == $direction->id) selected @endif
                    >
                    {{ $direction->name }} ({{ number_format($direction->price, 2) }} ₽)
                </option>
            @endforeach
        </select>
    </div>
</div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Сумма платежа (₽) *</label>
                        <input type="number" name="amount" id="amount" class="form-control" required min="1" step="0.01">
                        <small class="text-muted">Минимальная сумма: 1 ₽</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Способ оплаты *</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="card">Банковская карта</option>
                            <option value="transfer">Банковский перевод</option>
                            <option value="cash">Наличные</option>
                        </select>
                    </div>
                </div>
                
                <div class="alert alert-info mb-3" id="payment-info" style="display: none;">
                    <div>Полная стоимость направления: <span id="full-price">0</span> ₽</div>
                    <div>Уже оплачено: <span id="paid-amount">0</span> ₽</div>
                    <div>Остаток к оплате: <span id="remaining-amount">0</span> ₽</div>
                </div>
                
                <button type="submit" class="btn btn-primary">Создать платеж</button>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
    $('#direction-select').change(function() {
        const selected = $(this).find(':selected');
        const directionId = $(this).val();
        const fullPrice = parseFloat(selected.data('price')) || 0;
        
        if (directionId) {
            $('#payment-info').show();
            $('#full-price').text(fullPrice.toLocaleString('ru-RU'));
            
            // Загружаем оплаченную сумму через AJAX
            $.get(`/api/directions/${directionId}/paid-amount`, function(data) {
                const paidAmount = parseFloat(data.paid) || 0;
                const remaining = fullPrice - paidAmount;
                
                $('#paid-amount').text(paidAmount.toLocaleString('ru-RU'));
                $('#remaining-amount').text(remaining.toLocaleString('ru-RU'));
                $('#amount').attr('max', remaining > 0 ? remaining : fullPrice);
            });
        } else {
            $('#payment-info').hide();
        }
    });
});
        
        // Валидация формы
        $('#payment-form').submit(function(e) {
            const amount = parseFloat($('#amount').val());
            const remaining = parseFloat($('#remaining-amount').text().replace(/\s/g, '')) || 0;
            const fullPrice = parseFloat($('#full-price').text().replace(/\s/g, '')) || 0;
            const minAmount = 1;
            
            if (amount < minAmount) {
                alert(`Минимальная сумма платежа ${minAmount} ₽`);
                e.preventDefault();
                return false;
            }
            
            if (remaining > 0 && amount > remaining) {
                alert(`Сумма платежа не может превышать остаток ${remaining.toLocaleString('ru-RU')} ₽`);
                e.preventDefault();
                return false;
            }
            
            if (remaining <= 0 && amount > fullPrice) {
                alert(`Сумма платежа не может превышать полную стоимость ${fullPrice.toLocaleString('ru-RU')} ₽`);
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    });
</script>
@endsection
@endsection