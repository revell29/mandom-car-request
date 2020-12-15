<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <title>{{ $data->no_transaksi }}</title>

 <style>
   .table-header {
     border-collapse: collapse
   }

   .table-paraf {
     border-collapse: collapse;
     font-size: 10px;
   }

   .table-paraf  tr  td {
     border: 1px solid black;
     padding: 5px;
   }

   .table-item {
     border-collapse: collapse;
     font-size: 10px;
     width: 100%;
   }

   .table-item tr th {
     border: 1px solid rgb(109, 109, 109);
     padding: 10px;
     background-color: #d8d8d87a;
   }
   .table-item tr td {
     border: 1px solid rgb(109, 109, 109);
     padding: 10px;
   }
 </style>
</head>
<body>
 <div style="border: 1px solid black; padding: 12px;">
  <table class="table-header" width="100%">
    <tr>
      <td rowspan="5" style="border: 1px solid black;" width="30%">
        <img src="https://www.intidaya.org/wp-content/uploads/2018/10/mandom.png" width="150px" height="80px" style="text-align: center;" alt="">
      </td>
    </tr>
    <tr>
      <td style="border-top: 1px solid black; text-align: center;" rowspan="1">FORMULIR</td>
      <td style="border: 1px solid black;">No</td>
    </tr>
    <tr>
      <td style="border: 1px solid black; text-align: center;" rowspan="3">SURAT DINAS SUPIR</td>
      <td style="border-width: 0px 1px; border-style: solid;">Tgl Berlaku</td>
    </tr>
    <tr>
      <td style="border-width: 1px 1px; border-style: solid;" >No Rev</td>
    </tr>
    <tr>
      <td style="border-width: 1px 1px; border-style: solid;">Hal: -</td>
    </tr>
  </table>
  <div style="margin-top:20px; width: 40%; float: left; " >
    <table style="font-size: 10px;">
      <tr>
        <td  style="text-align: right; padding: 6px;" width="30%">NO TRANS: </td>
        <td>{{ $data->no_transaksi }}</td>
      </tr>
      <tr>
        <td  style="text-align: right;  padding: 6px;">SUPIR: </td>
        <td>{{ $data->supir->nama }}</td>
      </tr>
      <tr>
        <td  style="text-align: right;  padding: 6px;">NO POL: </td>
        <td>{{ $data->mobil->no_polisi }}</td>
      </tr>
      <tr>
        <td  style="text-align: right;  padding: 6px;">TYPE: </td>
        <td>{{ $data->mobil->merek_mobil }}</td>
      </tr>
      <tr>
        <td  style="text-align: right;  padding: 6px;">TANGGAL: </td>
        <td>{{ $data->date }}</td>
      </tr>
    </table>
  </div>
  <div style="width: 60%; float: left;">
      <div style="float: left;width: 30%;">CATATAN: </div>
      <div style="border-style: dotted; border-width: 1px; float: left; padding: 10px;">
        {{ $data->description }}
      </div>
  </div>
  <div style="margin-top 50px; float: left; width: 40%;">
    &nbsp;
  </div>
  <div style="margin-top 50px; float: left; width: 60%;">
  * Hanya untuk jam lembur
  </div>
  <div style="margin-top: 20px;">
    <div style="margin-top 50px; float: left; width: 40%;">
      &nbsp;
    </div>
    <div style="margin-top 50px; float: left; width: 60%;">
      <table class="table-paraf">
        <tr>
          <td>Pengecekan Security Awal</td>
          <td>Pengecekan Security Akhir</td>
          <td>Paraf Kordinator Supir</td>
        </tr>
        <tr>
          <td style="padding-top: 50px;">Jam</td>
          <td style="padding-top: 50px;">Jam</td>
          <td></td>
        </tr>
      </table>
    </div>
  </div>
  <div style="margin-top: 50px; width: 100%">
    <table class="table-item">
       <tr style="">
        <th>Karyawan</th>
        <th>Tujuan</th>
        <th>Mulai</th>
        <th>Selesai</th>
        <th>Paraf</th>
        <th>Keterangan</th>
       </tr>
       <tr>
         <td>{{ $data->employee->name }}</td>
         <td>{{ $data->destination }}</td>
         <td>{{ $data->start_time }}</td>
         <td>{{ $data->end_time }}</td>
         <td ></td>
         <td  width="30%">{{ $data->description }}</td>
       </tr>
    </table>
  </div>
 </div>
</body>
</html>