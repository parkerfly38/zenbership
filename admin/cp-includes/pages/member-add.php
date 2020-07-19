<?php



   
// Check permissions, ownership,
// and if it exists.
$permission = 'member-add';
$check = $admin->check_permissions($permission, $employee);
if ($check != '1') {
    $admin->show_no_permissions($error, '', '1');

} else {
    $cid             = generate_id($db->get_option('member_id_format'));
    $field           = new field;
    $final_form_col1 = $field->generate_form('member-add', '', '1');
    $final_form_col2 = $field->generate_form('member-add', '', '2');



    ?>

    <script src="js/jquery.accent-keyboard.js"></script>
    <script type="text/javascript">

        $.ctrl('S', function () {
            return json_add('member-add', '<?php echo $cid; ?>', '0', 'slider_form');
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

    <form action="" method="post" id="slider_form"
          onsubmit="return json_add('member-add','<?php echo $cid; ?>','0','slider_form');">


    <div id="slider_submit">
        <div class="pad24tb">

            <div id="slider_right">

                <input type="submit" value="Save" class="save"/>

            </div>

            <div id="slider_left">

                <a href="null.php" onclick="return popup('profile_picture','id=<?php echo $cid; ?>&type=member');"><img
                        src="<?php echo PP_ADMIN; ?>/imgs/anon.png" width="48" height="48" border="0" alt="" title=""
                        class="profile_pic border"/></a><span class="title">Creating Member</span><span class="data">Click here to upload a picture for this contact.</span>

            </div>

            <div class="clear"></div>

        </div>
    </div>


    <div id="primary_slider_content">

    <div class="col33">
        <div class="pad24_fs_l">


            <?php



            echo $final_form_col1;

            ?>


        </div>
    </div>

    <div class="col33">
        <div class="pad24_fs_r">


            <fieldset>

                <legend>General Details</legend>

                <div class="pad24t">

                    <div class="field">

                        <label>Type</label>

                        <div class="field_entry">

                            <select name="member_type" style="width:200px;">

                                <?php

                                echo $admin->member_types('');

                                ?>

                            </select>

                        </div>

                    </div>

                    <div class="field">

                        <label>Member No.</label>

                        <div class="field_entry">

                            <input type="text" value="<?php echo $cid; ?>" name="id"/>

                        </div>

                    </div>

                    <div class="field">

                        <label>Account</label>

                        <div class="field_entry">

                            <input type="text" value="" name="account_dud" id="faccount"
                                   onkeyup="return autocom(this.id,'id','name','ppSD_accounts','name','accounts');"
                                   style="width:250px;" class=""/><a href="null.php"
                                                                     onclick="return get_list('account','faccount_id','faccount');"><img
                                    src="imgs/icon-list.png" width="16" height="16" border="0"
                                    alt="Select from list" title="Select from list" class="icon-right"/></a><a
                                href="null.php" onclick="return popup('account-add','');"><img
                                    src="imgs/icon-quickadd.png" width="16" height="16" border="0" alt="Add"
                                    title="Add" class="icon-right"/></a>

                            <input type="hidden" name="account" id="faccount_id" value=""/>

                            <p class="field_desc" id="account_dud_dets">Accounts group similar contacts
                                together..</p>

                        </div>

                    </div>

                    <div class="field">

                        <label>Assign To</label>

                        <div class="field_entry">

                            <input type="text" id="ownerf" name="owner_dud"
                                   onkeyup="return autocom(this.id,'id','username','c7_staff','username,firstname,lastname','staff');"
                                   value="<?php echo $employee['username']; ?>" style="width:250px;"/><a
                                href="null.php" onclick="return get_list('staff','ownerf_id','ownerf');"><img
                                    src="imgs/icon-list.png" width="16" height="16" border="0"
                                    alt="Select from list" title="Select from list" class="icon-right"/></a>

                            <input type="hidden" name="owner" id="ownerf_id"
                                   value="<?php echo $employee['id']; ?>"/>

                            <p class="field_desc" id="owner_dud_dets">Select the employee to which you would like to
                                assign this contact.</p>

                        </div>

                    </div>

                    <div class="field">

                        <label>Source</label>

                        <div class="field_entry">

                            <input type="text" id="sourcef" name="source_dud"
                                   onkeyup="return autocom(this.id,'id','source','ppSD_sources','source','');"
                                   style="width:250px;"/><a href="null.php"
                                                            onclick="return get_list('source','sourcef_id','sourcef');"><img
                                    src="imgs/icon-list.png" width="16" height="16" border="0"
                                    alt="Select from list" title="Select from list" class="icon-right"/></a><a
                                href="null.php" onclick="return popup('sources','');"><img
                                    src="imgs/icon-quickadd.png" width="16" height="16" border="0" alt="Add"
                                    title="Add" class="icon-right"/></a>

                            <input type="hidden" name="source" id="sourcef_id"/>

                            <p class="field_desc" id="source_dud_dets">Where did you generate this lead?</p>

                        </div>

                    </div>

                    <div class="field">

                        <label>Created</label>

                        <div class="field_entry">

                            <?php

                            echo $af
                                ->setSpecialType('datetime')
                                ->setValue(current_date())
                                ->string('created');

                            //echo $admin->datepicker('created', current_date(), '1');

                            ?>

                        </div>

                    </div>

                    <div class="field">

                        <label>E-Mail Preference</label>

                        <div class="field_entry">

                            <select name="email_pref">

                                <option value="">No Preference</option>

                                <option value="html">HTML Format</option>

                                <option value="text">Plain Text</option>

                            </select>

                        </div>

                    </div>

                </div>

            </fieldset>


            <fieldset>

                <legend>Notify User?</legend>

                <div class="pad24t">

                    <div class="field">

                        <input type="radio" name="notify" value="1" checked="checked"/> Notify user of new
                        membership<br/>

                        <input type="radio" name="notify" value="0"/> Do not notify user of new membership<br/>

                    </div>

                </div>

            </fieldset>



            <?php

            echo $final_form_col2;

            ?>


        </div>
    </div>

    <div class="clear"></div>

    </div>


    </form>

    <script type="text/javascript" src="<?php echo PP_ADMIN; ?>/js/forms.js"></script>



<?php

}

?>