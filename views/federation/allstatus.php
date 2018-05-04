<div id="content" class="container-fluid dark-opac-bg">
  <div id="allstatus">
    <table class="table table-dark">
      <thead>
        <tr>
          <th scope="col">EID</th>
          <th scope="col">Team #</th>
          <th scope="col">Short Name</th>
          <th scope="col">Long Name</th>
          <th scope="col">Store Status</th>
        </tr>
      </thead>
      <tbody id="status-table">
      </tbody>
    </table>
    <script>
      $(document).ready(function () {
        var showList = $('#status-table');
        var className = 'status-yellow outline-dark';
        $.ajax({
          type: 'GET',
          url: '/~ct310/yr2018sp/master.json',
          async: true,
          success: function (master) {
            console.log(master);
            var teams = master.map(function (item) {
              $.ajax({
                type: 'GET',
                url: '/~' + item.eid + '/ct310/index.php/federation/status',
                async: true,
                success: function (result) {
                  var jsobj;
                  try {
                    jsobj = JSON.parse(result);
                  } catch (e) {
                    jsobj = result;
                  }
                  //console.log(jsobj);
                  if(jsobj.status === 'open') {
                    className = 'status-green outline-dark';
                  } else if(jsobj.status === 'closed') {
                    className = 'status-red outline-dark';
                  } else {
                    var className = 'status-yellow outline-dark';
                  }
                  showList.append('<tr>' +
                    '<td>' + item.eid + '</td>' +
                    '<td>' + item.team + '</td>' +
                    '<td>' + item.nameShort + '</td>' +
                    '<td>' + item.nameLong + '</td>' +
                    '<td class=\"' + className + '\">' + jsobj.status + '</td>' +
                    '</tr>'
                  );
                }
              });
            });
          }
        });
      });
    </script>
  </div>
</div>


