<?php
function aatop_admin_menu_html()
{
    ?>
    <h1>Events </h1>
    <table class="aatop-table">
        <thead>
            <tr>
                <th >User Id</th>
                <th >Type</th>
                <th >TAGS</th>
            </tr>
        </thead>
        <tbody>   
    <?php
        global $wpdb;
        $table_visitors = $wpdb->prefix . 'aatop_visitors';
        $resultsap = $wpdb->get_results("SELECT * FROM " . $table_visitors);
        require_once plugin_dir_path(__FILE__) . 'tracking.php';
        foreach ($resultsap as $row):
            $tags = AatopTracking::getTags($row->session_id);
            $search = implode(",", array_keys($tags["search"] ?? []));
            $vacature = implode(",", array_keys($tags["vacature"] ?? []));
            ?>
                    <tr class="aatop-result">     
                        <td ><?=$row->session_id?></td>
                        <td >vacature</td>
                        <td ><?=$vacature?></td>
                    </tr>
                    <tr class="aatop-result">     
                        <td ><?=$row->session_id?></td>
                        <td >search</td>
                        <td ><?=$search?></td>
                    </tr>
            <?php
        endforeach;
    ?>
        </tbody>
    </table>
    <?php
}


function aatop_admin_menu_post_html()
{
    ?>
    <table class="aatop-table">
        <thead>
            <tr>
                <th >ID</th>
                <th >IDENTIFIED</th>
                <th >EMAIL OPTIN</th>
                <th >EMAIL PAUSED AT</th>
                <th >EMAIL PAUSED PERIOD</th>
            </tr>
        </thead>
        <tbody>   
    <?php
            global $wpdb;
            $table_session = $wpdb->prefix . 'aatop_session';
            $resultsap = $wpdb->get_results("SELECT * FROM " . $table_session);
    foreach ($resultsap as $row):
        ?>
                <tr class="aatop-result">
                    <td ><?=$row->id?></td>
                    <td ><?=$row->identified?></td> 
                    <td ><?=$row->email_optin?></td> 
                    <td ><?=$row->email_paused_at?></td>
                    <td ><?=$row->email_paused_period?></td>
                </tr>
        <?php
    endforeach;
    ?>
        </tbody>
    </table>
    <?php
}

function aatop_admin_menu_visitors_html()
{
    ?>
    <table class="aatop-table">
        <thead>
            <tr>
                <th >ID</th>
                <th >CreatedAt</th>
                <th >Identifier</th>
                <th >Email</th>
                <th >Push_idCREATED AT</th>
                <th >Email_option</th>
                <th >Email_paused_at</th>
                <th >Email_paused_period</th>
                <th >Subscription_tags</th>
            </tr>
        </thead>
        <tbody>   
    <?php
            global $wpdb;
            $table_visitors = $wpdb->prefix . 'aatop_visitors';
            $resultsap = $wpdb->get_results("SELECT * FROM " . $table_visitors);
    foreach ($resultsap as $row):
        ?>
                <tr class="aatop-result">
                    <td ><?=$row->id?></td>
                    <td ><?=$row->type?></td> 
                    <td ><?=$row->session_id?></td> 
                    <td ><?=$row->created_at?></td>
                </tr>
        <?php
    endforeach;
    ?>
        </tbody>
    </table>
    <?php
}
