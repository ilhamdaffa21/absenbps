<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    @media print {
        @page {
            margin-top: 30px;
        }

        .btn,
        .last,
        footer,
        a#debug-icon-link,
        label,
        .dataTables_info,
        .dataTables_paginate,
        .paging_simple_numbers {
            display: none;
        }
    }
</style>

<div class="container-fluid">
    <!-- Content Wrapper. Contains page content -->
    <?php if (session()->getFlashData('error')) { ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: '<?= session()->getFlashData('error') ?>',
                showConfirmButton: true,
            })
        </script>
    <?php } ?>

    <?php if (session()->getFlashData('success')) { ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: '<?= session()->getFlashData('success') ?>',
                showConfirmButton: true,
            })
        </script>
    <?php } ?>

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <h1 class="m-0">Data Bidang</h1>
                <div class="ml-auto">
                    <!-- tombol tambah data -->
                    <button onclick="window.print()" class="btn-sm btn-outline-secondary ml-auto mr-1" style="height:38px;">Cetak <i class="fa fa-print"></i></button>
                    <a class="btn btn-primary float-right" id="btnTambah" href="<?php echo base_url("bidang/new") ?>" role="button">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </div><!-- /.col -->
            </div><!-- /.col -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card card-primary card-outline">
                        <div class="card-body table-responsive">
                            <table id="tabel-bidang" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Bidang</th>
                                        <th class="last">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $row) : ?>

                                        <tr>
                                            <td><?php echo $no++ ?></td>
                                            <td><?= $row['nama_bidang']; ?></td>
                                            <td class="last">
                                                <a href="/bidang/show/<?= $row['id']; ?>" class="btn btn-primary mr-1 ml-1">Detail</a>
                                                <a href="/bidang/edit/<?= $row['id']; ?>" class="btn btn-warning mr-1 ml-1">Edit</a>
                                                <form action="/bidang/delete/<?= $row['id']; ?>" method="post" class="d-inline">
                                                    <button type="submit" class="btn btn-danger mr-1 ml-1 delete">Delete</button>
                                                </form>
                                            </td>

                                        </tr>

                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <div class="row">

            </div>
            <?php

            ?>

            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Ini Home Page</h1>
          </div>
    </div> -->
<!-- </div> -->

<script src="<?= base_url() ?>/template/plugins/jquery/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">

<script>
    $(document).ready(function() {
        $('#tabel-bidang').DataTable();
    });
</script>

<script>
    $('.delete').on('click', function(e) {
        e.preventDefault();
        var form = $(this).parents('form');
        Swal.fire({
            title: 'Apakah anda yakin?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus data!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    });
</script>