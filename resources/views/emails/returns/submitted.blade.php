@component('mail::message')
# Новая заявка на возврат

Поступила новая заявка на возврат товара.

**Информация о возврате:**

- **Номер заявки:** {{ $return->id }}
- **Номер заказа:** {{ $return->order_id }}
- **Дата заявки:** {{ $return->created_at->format('d.m.Y H:i') }}
- **Клиент:** {{ $return->order->user->name }}
- **Email клиента:** {{ $return->order->user->email }}

**Информация о товаре:**

- **Книга:** {{ $return->book->title }}
- **Автор:** {{ $return->book->author }}
- **ISBN:** {{ $return->book->isbn }}
- **Количество для возврата:** {{ $return->return_quantity }}

**Причина возврата:**
{{ $return->return_reason }}

@component('mail::button', ['url' => route('admin.returns.show', $return->id)])
Просмотреть заявку
@endcomponent

С уважением,<br>
{{ config('app.name') }}
@endcomponent

