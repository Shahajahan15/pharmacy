 <a href="javascript:void(0)" class="closebtn" onclick="closeNav()" title="Close">&times;</a>
 <div style="margin-top: 20px">
    <ul class="list-unstyled">
    <?php foreach ($records as $record) { ?>
  <li><a class="short_list_test" id="<?php echo $record->id; ?>"><?php echo $record->test_name; ?></a></li>
  <?php } ?>
  </ul>
  </div>
  