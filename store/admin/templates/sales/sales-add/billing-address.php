          <?php
          if (!defined('SALE_ADD')) {
            exit;
          }
          ?>
          <label><?php echo $msg_admin_viewsale3_0[2] . ' ' . $msg_viewsale37; ?>:</label>
          <div class="form-group input-group">
            <span class="input-group-addon"><a href="#" onclick="mc_fieldCopy('billing');return false" title="<?php echo mc_safeHTML($msg_admin_viewsale3_0[12]); ?>"><i class="fa fa-copy fa-fw"></i></a></span>
            <input type="text" class="box addon-no-radius" name="bill_1" value="">
          </div>

          <label style="margin-top:10px"><?php echo $msg_admin_viewsale3_0[2] . ' ' . $msg_viewsale39; ?>:</label>
          <input type="text" class="box" name="bill_2" value="">

          <label style="margin-top:10px"><?php echo $msg_admin_viewsale3_0[2] . ' ' . $msg_viewsale99; ?>:</label>
          <input type="text" class="box" name="bill_3" value="">

          <label style="margin-top:10px"><?php echo $msg_admin_viewsale3_0[2] . ' ' . $msg_viewsale100; ?>:</label>
          <input type="text" class="box" name="bill_4" value="">

          <label style="margin-top:10px"><?php echo $msg_admin_viewsale3_0[2] . ' ' . $msg_viewsale101; ?>:</label>
          <input type="text" class="box" name="bill_5" value="">

          <label style="margin-top:10px"><?php echo $msg_admin_viewsale3_0[2] . ' ' . $msg_viewsale102; ?>:</label>
          <input type="text" class="box" name="bill_6" value="">

          <label style="margin-top:10px"><?php echo $msg_admin_viewsale3_0[2] . ' ' . $msg_viewsale103; ?>:</label>
          <input type="text" class="box" name="bill_7" value="">

          <label style="margin-top:10px"><?php echo $msg_viewsale104; ?>:</label>
          <select name="bill_9">
          <?php
          $q_ctry   = mysqli_query($GLOBALS["___msw_sqli"], "SELECT * FROM `" . DB_PREFIX . "countries`
                      WHERE `enCountry`  = 'yes'
                      ORDER BY `cName`
                      ") or die(mc_MySQLError(__LINE__,__FILE__));
          while ($CTY = mysqli_fetch_object($q_ctry)) {
          ?>
          <option value="<?php echo $CTY->id; ?>"<?php echo ($SETTINGS->shipCountry==$CTY->id ? ' selected="selected"' : ''); ?>><?php echo mc_safeHTML($CTY->cName); ?></option>
          <?php
          }
          ?>
          </select>