   <?php


      $msg="This is test of SCV";
      // $firsname=$title;
    ?>

<center>
  <style type="text/css">
   h3
   {
    color: black;
    font-weight: bold;
    display: inline;
   }
   a
   {
    text-decoration: none;
    display: inline;
   }
   table
   {
    border: 2px solid black; width: 400px;
   }
  table > thead
   {
    background-color: #68d5d53b;
   }
  </style>
<table cellpadding="30" style="border: 2px solid #001D3D;">
  <thead style="background:#001D3D">
    <tr>
      <th>
        <center>
         <img src="https://scv.gantzerdev.com/img/logo.8488106a.png">
      </center>
    </th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <h3>Hi SCV Admin</h3>
      </td>
    </tr>
    <tr>
      <td>
         <p>
          Someone submitted the Contact Us from. Here is the submitted information:
        </p>
        <h5>First Name: {{$first_name}}</h5>
        <h5>Last Name: {{$last_name}}</h5>
        <h5>Email: {{$email}}</h5>
        <h5>Phone: {{$phone}}</h5>
        <h5>Preferred Method of Contact: {{$contact_method}}</h5>
      </td>
    </tr>
    <tr>
    	<td>
     <p>Best Regards</p>
     <p>Smart College Visit Team</p>
    	</td>
    </tr>
     </tbody>
  
  
  <tfoot  style="background:#001D3D">
  <tr>
    <td style="text-align: center; color:#fff;">© Copyright @php
      echo date('Y');
    @endphp SCV All right reserved</td>
  </tr>
  </tfoot>
</table>
</center>

