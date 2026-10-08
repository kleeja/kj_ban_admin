<?php
// Kleeja Plugin
// kj_ban_admin
// Version: 1.1
// Developer: Kleeja team

// Prevent illegal run
if (!defined('IN_PLUGINS_SYSTEM')) {
    exit();
}

// Plugin Basic Information
$kleeja_plugin['kj_ban_admin']['information'] = [
    // The casual name of this plugin, anything can a human being understands
    'plugin_title' => [
        'en' => 'Admin Firewall',
        'ar' => 'جدار حماية لوحة التحكم',
    ],
    // Who wrote this plugin?
    'plugin_developer' => 'Kleeja.net',
    // This plugin version
    'plugin_version' => '1.1',
    // Explain what is this plugin, why should I use it?
    'plugin_description' => [
        'en' =>
            'Bans the IP address of anyone who enters a wrong password 10 times when signing in to the control panel',
        'ar' => 'يحظر عنوان IP لكل من يُدخل كلمة مرور خاطئة 10 مرات عند تسجيل الدخول إلى لوحة التحكم',
    ],
    // Min version of Kleeja that's requiered to run this plugin
    'plugin_kleeja_version_min' => '4.0.0',
    // Max version of Kleeja that support this plugin, use 0 for unlimited
    'plugin_kleeja_version_max' => '4.9',
    // Should this plugin run before others?, 0 is normal, and higher number has high priority
    'plugin_priority' => 0,
];

//after installation message, you can remove it, it's not requiered
$kleeja_plugin['kj_ban_admin']['first_run']['ar'] = "
تحمي هذه الإضافة تسجيل الدخول إلى لوحة التحكم من تخمين كلمات المرور: إذا أُدخلت كلمة مرور خاطئة 10 مرات من عنوان IP واحد،
أُضيف العنوان إلى قائمة «التحكم بالحظر» ولم يعد بإمكانه فتح أي صفحة من الموقع.
<br>
إذا حُظر عنوانك، فاحذفه من «التحكم بالحظر» عبر شبكة أخرى، أو اتبع خطوات استعادة الدخول في دليل الإضافة.
<br>
<a href='./index.php?cp=s_help#help-kj_ban_admin'>دليل الإضافة</a> -
<a href='./index.php?cp=k_ban'>التحكم بالحظر</a> -
<a href='https://github.com/kleeja/kj_ban_admin/issues' target='_blank' rel='noopener'>الإبلاغ عن مشكلة</a>
";

$kleeja_plugin['kj_ban_admin']['first_run']['en'] = "
This plugin protects the sign-in of the control panel from password guessing: after 10 wrong passwords from one IP address,
the address is added to Ban Control and can no longer open any page of the site.
<br>
If your own address is banned, delete it in Ban Control from another network, or follow the recovery steps in the guide of the plugin.
<br>
<a href='./index.php?cp=s_help#help-kj_ban_admin'>Plugin guide</a> -
<a href='./index.php?cp=k_ban'>Ban Control</a> -
<a href='https://github.com/kleeja/kj_ban_admin/issues' target='_blank' rel='noopener'>Report a problem</a>
";

// Plugin Installation function
$kleeja_plugin['kj_ban_admin']['install'] = function ($plg_id) {
    kj_ban_admin_create_table();
};

// 1.0 counted the attempts in the session, which a new session starts again, 1.1 counts them by address in a table.
// The queries are direct ones: the core helpers run hooks, and the plugins are still loading
$kleeja_plugin['kj_ban_admin']['update'] = function ($old_version, $new_version) {
    if (version_compare($old_version, '1.1', '<')) {
        kj_ban_admin_create_table();
    }
};

// Plugin Uninstallation, function to be called at unistalling.
// The addresses that the plugin banned stay in Ban Control, they are bans like the others now
$kleeja_plugin['kj_ban_admin']['uninstall'] = function ($plg_id) {
    global $SQL, $dbprefix;

    // Kleeja calls this with the cursor of its last query (the id of the plugin) still open,
    // and SQLite drops no table while a query is open: "database table is locked"
    $SQL->freeresult();

    $SQL->query("DROP TABLE IF EXISTS `{$dbprefix}kj_ban_admin_attempts`");
};

// Plugin functions
$kleeja_plugin['kj_ban_admin']['functions'] = [
    // the ban list of Kleeja stops IPv4 addresses on every page, but it never matches an IPv6 address,
    // so an IPv6 address that the plugin banned is stopped here, before its password is checked
    'admin_login_submit' => function ($args) {
        global $banss, $lang;

        $ip = get_ip();

        if ($ip === '' || !in_array($ip, array_map('trim', (array) $banss), true)) {
            return;
        }

        $ERRORS = $args['ERRORS'];
        $ERRORS[] = $lang['U_R_BANNED'];

        return compact('ERRORS');
    },

    // the sign-in form is shown again after a failed sign-in (a successful one redirects before this hook),
    // and only a wrong password counts: the other errors stop the sign-in before the password is checked
    'before_display_template_admin_page' => function ($args) {
        global $lang;

        if (ip('submit') && in_array($lang['LOGIN_ERROR'], (array) ($args['ERRORS'] ?? []), true)) {
            kj_ban_admin_failed_attempt(get_ip());
        }
    },

    // the first page after a successful sign-in, the address starts again from zero
    'begin_admin_page' => function ($args) {
        if (!empty($_SESSION['kj_ban_admin_failed'])) {
            unset($_SESSION['kj_ban_admin_failed']);
            kj_ban_admin_forget(get_ip());
        }
    },

    // the guide of the plugin on the help page of the control panel, its words are in language/help_{code}.php
    'admin_help_guides' => function ($args) {
        $help_guides = $args['help_guides'];
        $words = kj_ban_admin_help_words();

        // the name of the plugin is the key, so Kleeja knows that the plugin has its guide, and shows its icon.
        // The plugin has no page, the guide links to Ban Control, where its bans are,
        // and has no 'page', so the help button of Ban Control still opens the guide of Kleeja, which links to it
        $help_guides['kj_ban_admin'] = [
            'group' => 'plugins',
            'title' => $words['KJ_BAN_ADMIN_HELP_TITLE'],
            'intro' => $words['KJ_BAN_ADMIN_HELP_INTRO'],
            'link' => './?cp=k_ban',
            // tips and warnings are shown beside the others on wide screens
            'sections' => [
                kj_ban_admin_help_section($words, 'features', 'FEATURE'),
                kj_ban_admin_help_section($words, 'steps', 'STEP'),
                kj_ban_admin_help_section($words, 'faq', 'FAQ'),
                kj_ban_admin_help_section($words, 'tips', 'TIP'),
                kj_ban_admin_help_section($words, 'warnings', 'WARNING'),
            ],
        ];

        if (isset($help_guides['ban'])) {
            $help_guides['ban']['sections'][] = ['type' => 'text', 'text' => $words['KJ_BAN_ADMIN_HELP_NOTE']];
        }

        return compact('help_guides');
    },
];

/**
 * special functions
 */

if (!function_exists('kj_ban_admin_failed_attempt')) {
    // the wrong passwords that ban an address
    define('KJ_BAN_ADMIN_MAX_ATTEMPTS', 10);
    // the seconds without a wrong password after which an address starts again from zero
    define('KJ_BAN_ADMIN_RESET_AFTER', 86400);

    /**
     * the table of the wrong passwords, one row for each address that has some
     * @return void
     */
    function kj_ban_admin_create_table()
    {
        global $SQL, $dbprefix;

        $SQL->query(
            "CREATE TABLE IF NOT EXISTS `{$dbprefix}kj_ban_admin_attempts` (
                `ip` varchar(45) collate utf8_bin NOT NULL,
                `attempts` smallint(5) unsigned NOT NULL DEFAULT '0',
                `last_attempt` int(11) unsigned NOT NULL DEFAULT '0',
                PRIMARY KEY (`ip`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;",
        );
    }

    /**
     * a wrong password from this address, the last one allowed bans it
     * @param  string $ip
     * @return void
     */
    function kj_ban_admin_failed_attempt($ip)
    {
        global $SQL, $dbprefix, $config;

        if ($ip === '') {
            return;
        }

        $now = time();

        // the addresses that had no wrong password for a while are forgotten
        $SQL->build([
            'DELETE' => "{$dbprefix}kj_ban_admin_attempts",
            'WHERE' => 'last_attempt < :expired',
            'BIND' => ['expired' => $now - KJ_BAN_ADMIN_RESET_AFTER],
        ]);

        $result = $SQL->build([
            'SELECT' => 'attempts',
            'FROM' => "{$dbprefix}kj_ban_admin_attempts",
            'WHERE' => 'ip = :ip',
            'BIND' => ['ip' => $ip],
        ]);

        $row = $SQL->fetch_array($result);
        $SQL->freeresult($result);

        $attempts = (int) ($row['attempts'] ?? 0) + 1;

        if ($attempts < KJ_BAN_ADMIN_MAX_ATTEMPTS) {
            $SQL->build(
                $row
                    ? [
                        'UPDATE' => "{$dbprefix}kj_ban_admin_attempts",
                        'SET' => 'attempts = :attempts, last_attempt = :now',
                        'WHERE' => 'ip = :ip',
                        'BIND' => ['attempts' => $attempts, 'now' => $now, 'ip' => $ip],
                    ]
                    : [
                        'INSERT' => 'ip, attempts, last_attempt',
                        'INTO' => "{$dbprefix}kj_ban_admin_attempts",
                        'VALUES' => ':ip, :attempts, :now',
                        'BIND' => ['ip' => $ip, 'attempts' => $attempts, 'now' => $now],
                    ],
            );

            // a successful sign-in in this session clears the count, see begin_admin_page
            $_SESSION['kj_ban_admin_failed'] = true;

            return;
        }

        // banned: Ban Control is where the address is now, its count is not needed anymore
        kj_ban_admin_forget($ip);
        kj_ban_admin_ban($ip);

        unset($_SESSION['kj_ban_admin_failed']);
        redirect($config['siteurl']);
    }

    /**
     * the address starts again from zero
     * @param  string $ip
     * @return void
     */
    function kj_ban_admin_forget($ip)
    {
        global $SQL, $dbprefix;

        $SQL->build([
            'DELETE' => "{$dbprefix}kj_ban_admin_attempts",
            'WHERE' => 'ip = :ip',
            'BIND' => ['ip' => $ip],
        ]);
    }

    /**
     * the address is added to the ban list of Kleeja, the one of Ban Control
     * @param  string $ip
     * @return void
     */
    function kj_ban_admin_ban($ip)
    {
        global $SQL, $dbprefix;

        $result = $SQL->build([
            'SELECT' => 'ban',
            'FROM' => "{$dbprefix}stats",
        ]);

        $row = $SQL->fetch_array($result);
        $SQL->freeresult($result);

        // read as Ban Control reads it, an empty list is not one empty item.
        // The items are saved encoded already, and an address has nothing to encode
        $banned_items = array_values(array_filter(explode('|', (string) ($row['ban'] ?? ''))));

        if (in_array($ip, array_map('trim', $banned_items), true)) {
            return;
        }

        $banned_items[] = $ip;

        $SQL->build([
            'UPDATE' => "{$dbprefix}stats",
            'SET' => 'ban = :ban',
            'BIND' => ['ban' => implode('|', $banned_items)],
        ]);

        if ($SQL->affected()) {
            // the cached list is a PHP file, and OPcache would keep running its old version for a while,
            // or until PHP restarts when it doesn't check the files, so the ban would wait too.
            // Before the file is deleted, OPcache finds a relative path only when the file exists
            if (function_exists('opcache_invalidate')) {
                @opcache_invalidate(PATH . 'cache/data_ban.php', true);
            }

            delete_cache('data_ban');
        }
    }

    /**
     * the words of the guide on the help page, in the language of the admin,
     * a word that the translation misses is shown in English
     * @return array
     */
    function kj_ban_admin_help_words()
    {
        global $config;

        $words = (array) require __DIR__ . '/language/help_en.php';
        $language = preg_replace('/[^a-z0-9_-]/i', '', (string) ($config['language'] ?? ''));
        $translation = __DIR__ . "/language/help_{$language}.php";

        if ($language !== '' && $language !== 'en' && file_exists($translation)) {
            // in its own line, Prettier drops the brackets of (require $translation) + $words
            $translated = require $translation;
            $words = (array) $translated + $words;
        }

        return $words;
    }

    /**
     * a section of the guide from its numbered words, like KJ_BAN_ADMIN_HELP_TIP_1, KJ_BAN_ADMIN_HELP_TIP_2 ..
     * its title, when it has its own, is KJ_BAN_ADMIN_HELP_TIP_TITLE
     * @param  array  $words
     * @param  string $type  how Kleeja shows it: features, steps, tips, warnings or faq
     * @param  string $name  the name of its words, KJ_BAN_ADMIN_HELP_{name}_1
     * @return array
     */
    function kj_ban_admin_help_section($words, $type, $name)
    {
        $prefix = 'KJ_BAN_ADMIN_HELP_' . $name;
        $section = ['type' => $type, 'title' => $words[$prefix . '_TITLE'] ?? '', 'items' => []];

        for ($n = 1; isset($words[$prefix . ($type == 'faq' ? '_Q_' : '_') . $n]); $n++) {
            $section['items'][] =
                $type == 'faq'
                    ? ['q' => $words[$prefix . '_Q_' . $n], 'a' => $words[$prefix . '_A_' . $n] ?? '']
                    : $words[$prefix . '_' . $n];
        }

        return $section;
    }
}
