<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <title>Report Car Request</title>

 <style>
   body {
    font-family: Arial, Helvetica, sans-serif;
   }
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
     font-size: 9px;
     width: 100%;
   }

   .table-item tr th {
     border: 1px solid #c0c0c0;
     padding: 4px;
     background-color: #c0c0c0;
   }
   .table-item tr td {
     border: 1px solid #c0c0c0;
     padding: 4px;
   }
 </style>
</head>
<body>
  <div style="text-align: center">
    <h4>REPORT CAR REQUEST BY STATUS</h4>
  </div>
  <div style="margin-bottom: 20px; font-size: 10px;">
    <label for="">Tanggal:</label>
    <label for="" style="margin-left: 5px;">{{ $request->daterange }}</label>
    <label for="" style="margin-left: 25px;">Status:</label>
    <label for="" style="margin-left: 5px;">{{ $request->status }}</label>

  </div>
  <table class="table-item">
    <tr style="">
     <th>No Transaksi</th>
     <th>Date</th>
     <th>Karyawan</th>
     <th>Destination</th>
     <th>Start</th>
     <th>End</th>
     <th>Keterangan</th>
     <th>Driver</th>
     <th>Type Supir</th>
     <th>Type Mobil</th>
     <th>No Pol</th>
    </tr>
    @foreach($data as $datas)
      <tr>
        <td>{{ $datas->no_transaksi }}</td>
        <td>{{ $datas->date }}</td>
        <td>{{ $datas->employee->name }}</td>
        <td>{{ $datas->destination }}</td>
        <td width="5%">{{ $datas->start_time }}</td>
        <td width="5%">{{ $datas->end_time }}</td>
        <td >{{ $datas->description }}</td>
        <td>{{ $datas->supir->nama }}</td>
        <td>{{ $datas->supir->type }}</td>
        <td>{{ $datas->mobil->merek_mobil }}</td>
        <td>{{ $datas->mobil->no_polisi }}</td>
      </tr>
    @endforeach
 </table>
</body>
</html>