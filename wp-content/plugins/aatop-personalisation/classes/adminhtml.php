<?php
function aatop_admin_menu_html()
{
    ?>
        <h1>Testing 1</h1>
    <?php
}

function aatop_admin_menu_post_html()
{
    ?>
    <table class="aatop-table">
        <thead>
            <tr>
                <th colspan="2">ID</th>
                <th colspan="2">IDENTIFIED</th>
                <th colspan="2">EMAIL OPTIN</th>
                <th colspan="2">EMAIL PAUSED AT</th>
                <th colspan="2">EMAIL PAUSED PERIOD</th>
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
                    <td colspan="2"><?=$row->id?></td>
                    <td colspan="2"><?=$row->identified?></td> 
                    <td colspan="2"><?=$row->email_optin?></td> 
                    <td colspan="2"><?=$row->email_paused_at?></td>
                    <td colspan="2"><?=$row->email_paused_period?></td>
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
                <th colspan="2">ID</th>
                <th colspan="2">TYPE</th>
                <th colspan="2">SESSION ID</th>
                <th colspan="2">CREATED AT</th>
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
                    <td colspan="2"><?=$row->id?></td>
                    <td colspan="2"><?=$row->type?></td> 
                    <td colspan="2"><?=$row->session_id?></td> 
                    <td colspan="2"><?=$row->created_at?></td>
                </tr>
    <?php
            endforeach;
    ?>
        </tbody>
    </table>
    <?php
}
