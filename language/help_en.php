<?php
//
// kj_ban_admin, its guide on the help page of the control panel
// English
//
// The guide is built by the admin_help_guides hook in init.php, the texts here can have HTML.
//  - a list is numbered: KJ_BAN_ADMIN_HELP_TIP_1, KJ_BAN_ADMIN_HELP_TIP_2 ... it ends at the first missing number,
//    so an item can be added or removed without touching the code
//  - KJ_BAN_ADMIN_HELP_{LIST}_TITLE is the title of the list, a list without it takes the title that Kleeja gives its type
//  - questions and answers are KJ_BAN_ADMIN_HELP_FAQ_Q_1 and KJ_BAN_ADMIN_HELP_FAQ_A_1
//  - names in <strong> are written as the control panel shows them, the words of Kleeja are in lang/en/
//  - the numbers are KJ_BAN_ADMIN_MAX_ATTEMPTS and KJ_BAN_ADMIN_RESET_AFTER of init.php
//

return [
    'KJ_BAN_ADMIN_HELP_TITLE' => 'Admin Firewall',
    'KJ_BAN_ADMIN_HELP_INTRO' =>
        'Protect the sign-in of the control panel from password guessing. An IP address that enters a wrong password 10 times is banned, and can no longer open any page of your site.',

    //
    // what the plugin does
    //
    'KJ_BAN_ADMIN_HELP_FEATURE_1' =>
        'Counts the wrong passwords entered at the sign-in of the control panel, separately for each IP address.',
    'KJ_BAN_ADMIN_HELP_FEATURE_2' =>
        'At the 10th wrong password, adds the address to <strong>Ban Control</strong>, next to the bans you added yourself.',
    'KJ_BAN_ADMIN_HELP_FEATURE_3' =>
        'A successful sign-in resets the count of the address, and so do 24 hours without a wrong password.',
    'KJ_BAN_ADMIN_HELP_FEATURE_4' =>
        'Only a wrong password counts. An empty field, a wrong captcha or an expired form stops the sign-in before the password is checked, so it doesn\'t count.',

    //
    // how to lift a ban
    //
    'KJ_BAN_ADMIN_HELP_STEP_TITLE' => 'Lift a ban',
    'KJ_BAN_ADMIN_HELP_STEP_1' =>
        'Open <strong>Ban Control</strong> from an address that isn\'t banned. If your own address is banned, use another network, such as the mobile data of your phone.',
    'KJ_BAN_ADMIN_HELP_STEP_2' => 'Find the banned IP address in the list.',
    'KJ_BAN_ADMIN_HELP_STEP_3' =>
        'Click <strong>Delete</strong> next to it. The address can open your site again at once, and starts again from zero.',

    //
    // common questions
    //
    'KJ_BAN_ADMIN_HELP_FAQ_Q_1' => 'My own address is banned and I have no other network. How do I get back in?',
    'KJ_BAN_ADMIN_HELP_FAQ_A_1' =>
        'Edit the database with a tool such as phpMyAdmin. In the <code>stats</code> table (<code>klj_stats</code> with the default prefix), remove your address from the <code>ban</code> column, along with the <code>|</code> that separates it from the others. Then delete the file <code>cache/data_ban.php</code>, so Kleeja reads the list again.',
    'KJ_BAN_ADMIN_HELP_FAQ_Q_2' => 'Where do I see the addresses that the plugin banned?',
    'KJ_BAN_ADMIN_HELP_FAQ_A_2' =>
        'In <strong>Ban Control</strong>. The plugin keeps no list of its own, so its bans and yours are in the same place, and you lift them the same way.',
    'KJ_BAN_ADMIN_HELP_FAQ_Q_3' => 'Does the plugin protect the sign-in page of the site too?',
    'KJ_BAN_ADMIN_HELP_FAQ_A_3' =>
        'No. It watches only the sign-in that the control panel asks for before it opens. Wrong passwords on the sign-in page of the site don\'t count.',
    'KJ_BAN_ADMIN_HELP_FAQ_Q_4' => 'What happens to the bans when I delete the plugin?',
    'KJ_BAN_ADMIN_HELP_FAQ_A_4' =>
        'They stay in <strong>Ban Control</strong> until you delete them. Only the counts of wrong passwords are deleted with the plugin.',

    //
    // beside the guide
    //
    'KJ_BAN_ADMIN_HELP_TIP_1' =>
        'The plugin slows down password guessing, it doesn\'t replace a strong password. Use a long one that you use nowhere else.',
    'KJ_BAN_ADMIN_HELP_TIP_2' =>
        'Check <strong>Ban Control</strong> from time to time. People on the same office or mobile network can share one IP address, and a ban blocks all of them.',

    'KJ_BAN_ADMIN_HELP_WARNING_1' =>
        'A ban blocks the whole site for that address, the control panel too. Before you rely on the plugin, make sure you can reach your database or another network.',
    'KJ_BAN_ADMIN_HELP_WARNING_2' =>
        'If your site runs behind a proxy or a CDN other than Cloudflare, Kleeja may see every visitor with the address of the proxy. One ban would then block everyone, you included.',

    //
    // in the guide of Ban Control, whose list the plugin adds to
    //
    'KJ_BAN_ADMIN_HELP_NOTE' =>
        'The <strong>Admin Firewall</strong> plugin adds IP addresses to this list by itself. <a href="#help-kj_ban_admin">Read its guide</a>.',
];
