<div id="content" class="container-fluid dark-opac-bg">
  <div id="attractionsList">
    <center><h2>Our Attractions:</h2></center>
    <?php if (empty($attractions)) {
      echo "<h4>None</h4>";
    } else {
      foreach ($attractions as $attraction):?>
        <form method = "post" onSubmit="return confirm('Are you sure you want to delete this?')">
          <a href="<?=Uri::create('index.php/federation/view_attraction/' . $attraction['attractionID']); ?>"><?=$attraction['name']; ?></a>
            <?php if (Auth::check() && Auth::get('group') === '10'): ?>
              <button type="submit" class="btn btn-danger btn-xs" name="delete_id" value="<?=$attraction['attractionID']?>">Delete</button>
            <?php endif;?>
        </form>
      <?php endforeach; } ?>
  </div>
  <div id="attractionsList">
    <center><h2>Federation Attractions:</h2></center>
    <div id="list"></div>
    <script>
    $(document).ready(function () {
      $.ajax({
        type: 'GET',
        url: 'http://cs.colostate.edu/~ct310/yr2018sp/master.json',
        async: false,
        success: function (data) {
          var showList = $('#list');
          console.log(data);
          var teams = data.map(function (item) {
            $.getJSON('http://cs.colostate.edu/~' + item.eid + '/ct310/index.php/federation/listing', function(jsobj) {
              var pages = jsobj.map(function(page) {
                if (page.name != null) {
                  showList.append('<a href="http://cs.colostate.edu/~isaach/ct310/index.php/federation/view_external_attraction/' + item.eid + '/' + page.id + '">' + page.name + '</a><br/>');
                }
              });
              return pages;
            });
          });
        }
      });
    });
    </script>
  </div>
</div>
