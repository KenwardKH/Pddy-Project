<div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" class="form-control" wire:model="email" placeholder="Masukkan email"
            wire:input="cariAkun">
    </div>
    <div class="form-group">
        <label for="name">Nama:</label>
        <input name="customerName" type="text" id="name" class="form-control" wire:model="name" readonly>
        <input name="customerId" type="text" id="customerId" class="form-control" wire:model="customerId" readonly hidden>
    </div>
    <div class="form-group">
        <label for="phone">Nomor HP:</label>
        <input name="customerPhone" type="text" id="phone" class="form-control" wire:model="phone" readonly>
    </div>
</div>
