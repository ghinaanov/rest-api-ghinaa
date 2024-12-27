<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
</head>
<body>
    <h1>Tambah Produk Baru</h1>
    <form action="{{ route('store.product') }}" method="POST">
        @csrf
        <label for="name">Nama Produk:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="price">Harga Produk:</label><br>
        <input type="number" id="price" name="price" required><br><br>

        <label for="description">Deskripsi Produk:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <button type="submit">Simpan</button>
    </form>
</body>
</html>
