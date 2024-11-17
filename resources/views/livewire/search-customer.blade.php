<div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" class="form-control" wire:model="email" placeholder="Masukkan email"
            wire:input="cariAkun">
    </div>
    <div class="form-group">
        <label for="name">Nama:</label>
        <input type="text" id="name" class="form-control" wire:model="name" readonly>
    </div>
    <div class="form-group">
        <label for="phone">Nomor HP:</label>
        <input type="text" id="phone" class="form-control" wire:model="phone" readonly>
    </div>
</div>
