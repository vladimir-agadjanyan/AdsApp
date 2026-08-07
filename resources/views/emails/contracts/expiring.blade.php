<h2>Срок действия договора подходит к концу</h2>

<p>
    До окончания договора
    <strong>№{{ $contract->number }}</strong>
    осталось <strong>{{ $days }}</strong> дней.
</p>

<p>
    Контрагент:
    <strong>{{ $contract->counterparty->name }}</strong>
</p>

<p>
    Дата окончания:
    <strong>{{ $contract->end_date->format('d.m.Y') }}</strong>
</p>

<p>
    Рекомендуем своевременно продлить договор или завершить работы.
</p>

<hr>

<p>
    С уважением,<br>
    AdsApp
</p>