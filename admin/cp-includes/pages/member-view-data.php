<?php


$user = new user;
$data = $user->get_user($_POST['id']);

$field = new field;
$final_form_col1 = $field->generate_form('member-edit', $data['data'], '1');
$final_form_col2 = $field->generate_form('member-edit', $data['data'], '2');

$q1 = $db->run_query("
  SELECT
      ppSD_fieldsets.id,
      ppSD_fieldsets.name
  FROM
      `ppSD_fieldsets_locations`
  JOIN
      `ppSD_fieldsets`
  ON
      ppSD_fieldsets.id=ppSD_fieldsets_locations.fieldset_id
  WHERE
      ppSD_fieldsets_locations.location='member-edit'
  ORDER BY
      ppSD_fieldsets.name ASC
");

$list = '<ul id="fieldset_list">';
$list .= '<li class="on" id="li-fs9999" onclick="return show_fieldset(\'9999\');">Member Overview</li>';
while ($row = $q1->fetch()) {
    $list .= '<li id="li-fs' . $row['id'] . '" onclick="return show_fieldset(\'' . $row['id'] . '\');">' . $row['name'] . '</li>';
}
$list .= '<li id="show_all" onclick="return show_fieldset(\'show_all\');">Show All</li>';
$list .= '</ul>';

$field = new field();

$final_form_col3 = '';

$q2 = $db->run_query("
    SELECT
        `id`,
        `form_id`,
        `user_id`,
        `form_name`,
        `date`
    FROM
        `ppSD_form_submit`
    WHERE
        `user_id`='" . $db->mysql_clean($data['data']['id']) . "'
    ORDER BY
        `date` DESC
");

$listA = '<ul id="fieldset_list">';

while ($row = $q2->fetch()) {
    $listA .= '<li id="li-fs' . $row['id'] . '" onclick="return show_fieldset(\'' . $row['id'] . '\');">' . $row['form_name'] . '<span class="small">' . $db->format_date($row['date'], 'Y/m/d g:ia') . '</span></li>';
    $dataA = $db->assemble_eav_data($row['id']);
    $final_form_col3 .= $db->format_eav_data($dataA, $row['id'], $row['form_name'], '1');
}

if (empty($final_form_col3)) {
    $listA .= '<li class="weak">No additional data to display.</li>';
}

$listA .= '</ul>';

?>

<script type="text/javascript">

    $.ctrl('S', function () {
        return json_add('member-add', '<?php echo $data['data']['id']; ?>', '1', 'slider_formA');
    });

</script>
<script>
        $("input[name*='hebrew']:not([name*='date'])").accentKeyboard({
            layout: 'il_HE',
            active_shift: true,
            active_caps: false,
            is_hidden: true,
            open_speed: 300,
            close_speed: 100,
            show_on_focus: true,
            hide_on_blur: true,
            trigger: undefined,
            enabled: true
        });
    </script>

<form action="null.php" method="post" id="slider_formA"
      onsubmit="return json_add('member-add','<?php echo $data['data']['id']; ?>','1','slider_formA');">


<div id="slide_home_left">


    <?php

    echo $list;

    ?>



    <?php

    echo $listA;

    ?>


</div>

<div id="slide_home_right">
<div class="pad24_fs_r" id="primary_fields">


<div class="marginbot right">

    <input type="submit" value="Save" class="save"/>

</div>


<fieldset id="fs9999">

    <legend>Member Details</legend>

    <div class="pad24t">


        <div class="field">

            <label>Type</label>

            <div class="field_entry">

                <select name="member_type" style="width:200px;">

                    <?php

                    echo $admin->member_types($data['data']['member_type']);

                    ?>

                </select>

            </div>

        </div>


        <div class="field">

            <label>Username</label>

            <div class="field_entry">

                <input type="text" value="<?php echo $data['data']['username']; ?>" name="username"
                       id="username" style="width:250px;"/>

            </div>

        </div>


        <div class="field">

            <label>Account</label>

            <div class="field_entry">

                <input type="text" value="<?php echo $data['account']['name']; ?>" name="account_dud"
                       id="faccount"
                       onkeyup="return autocom(this.id,'id','name','ppSD_accounts','name','accounts');"
                       style="width:250px;"/><?php if (!empty($data['account']['id'])) {
                    echo "<a href=\"null.php\" onclick=\"return load_page('account','view','" . $data['account']['id'] . "');\"><img src=\"imgs/icon-view.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"View\" title=\"View\" class=\"icon-right\" /></a>";

                } ?><a href="null.php" onclick="return get_list('account','faccount_id','faccount');"><img
                        src="imgs/icon-list.png" width="16" height="16" border="0" alt="Select from list"
                        title="Select from list" class="icon-right"/></a><a href="null.php"
                                                                            onclick="return popup('account-add','');"><img
                        src="imgs/icon-quickadd.png" width="16" height="16" border="0" alt="Add" title="Add"
                        class="icon-right"/></a>

                <input type="hidden" name="account" id="faccount_id"
                       value="<?php echo $data['account']['id']; ?>"/>

                <p class="field_desc" id="account_dud_dets">Accounts group similar contacts together.</p>

            </div>

        </div>

        <?php



        if ($employee['permissions']['admin'] == '1') {
            ?>

            <div class="field">

                <label>Assigned To</label>

                <div class="field_entry">

                    <input type="text" id="ownerf" name="owner_dud"
                           onkeyup="return autocom(this.id,'id','username','ppSD_staff','username,firstname,lastname','staff');"
                           value="<?php echo $data['owner']['username']; ?>" style="width:250px;"/><a
                        href="null.php" onclick="return get_list('staff','ownerf_id','ownerf');"><img
                            src="imgs/icon-list.png" width="16" height="16" border="0" alt="Select from list"
                            title="Select from list" class="icon-right"/></a>

                    <input type="hidden" name="owner" id="ownerf_id"
                           value="<?php echo $data['owner']['id']; ?>"/>

                    <p class="field_desc" id="owner_dud_dets">Select the employee to which you would like to
                        assign this contact.</p>

                </div>

            </div>

        <?php

        }

        ?>

        <div class="field">

            <label>Source</label>

            <div class="field_entry">

                <input type="text" id="sourcef" value="<?php echo $data['source']['source']; ?>"
                       name="source_dud"
                       onkeyup="return autocom(this.id,'id','source','ppSD_sources','source','');"
                       style="width:250px;"/><a href="null.php"
                                                onclick="return get_list('source','sourcef_id','sourcef');"><img
                        src="imgs/icon-list.png" width="16" height="16" border="0" alt="Select from list"
                        title="Select from list" class="icon-right"/></a><a href="null.php"
                                                                            onclick="return popup('sources','');"><img
                        src="imgs/icon-quickadd.png" width="16" height="16" border="0" alt="Add" title="Add"
                        class="icon-right"/></a>

                <input type="hidden" name="source" id="sourcef_id"
                       value="<?php echo $data['source']['id']; ?>"/>

                <p class="field_desc" id="source_dud_dets">Where did you generate this lead?</p>

            </div>

        </div>

        <div class="field">
            <label>Created</label>
            <div class="field_entry">
                <?php

                echo $af
                    ->setSpecialType('datetime')
                    ->setValue($data['data']['joined'])
                    ->string('joined');

                //echo $admin->datepicker('joined', $data['data']['joined'], '1');
                ?>
            </div>
        </div>

        <div class="field">
            <label>Last Updated</label>
            <div class="field_entry">
                <?php
                echo $af
                    ->setSpecialType('datetime')
                    ->setValue($data['data']['last_updated'])
                    ->string('last_updated');

                // echo $admin->datepicker('last_updated', $data['data']['last_updated'], '1');
                ?>
            </div>
        </div>

        <div class="field">
            <label>Last Renewal</label>
            <div class="field_entry">
                <?php

                echo $af
                    ->setSpecialType('datetime')
                    ->setValue($data['data']['last_renewal'])
                    ->string('last_renewal');

                //echo $admin->datepicker('last_renewal', $data['data']['last_renewal'], '1');
                ?>
            </div>
        </div>

        <div class="field">
            <label>E-Mail Preference</label>
            <div class="field_entry">

                <select name="email_pref">

                    <option value=""<?php if (empty($data['data']['email_pref'])) {
                        echo " selected=\"selected\"";
                    } ?>>No Preference
                    </option>

                    <option value="html"<?php if ($data['data']['email_pref'] == 'html') {
                        echo " selected=\"selected\"";
                    } ?>>HTML Format
                    </option>

                    <option value="text"<?php if ($data['data']['email_pref'] == 'text') {
                        echo " selected=\"selected\"";
                    } ?>>Plain Text
                    </option>

                </select>

            </div>

        </div>


        <div class="field">
            <label>Login Redirection</label>
            <div class="field_entry">
                <input type="text" value="<?php echo $data['data']['start_page']; ?>" name="start_page"
                       id="start_page" style="width:250px;" class="zen_url"/>
                <p class="field_desc" id="start_page_dud_dets">Would you like to redirect this member to a custom start page at login? If so, input a full URL (http://www.yoursite.com/redirect/here)</p>
            </div>
        </div>

    </div>

</fieldset>



<?php

echo $final_form_col1;

echo $final_form_col2;

echo $final_form_col3;

?>


</div>
</div>

<div class="clear"></div>


</form>


<script type="text/javascript" src="js/fs_rotator.js"></script>