<?php
//
// kj_ban_admin, its guide on the help page of the control panel
// Arabic
//
// The guide is built by the admin_help_guides hook in init.php, the texts here can have HTML.
// The keys are the ones of help_en.php, a key that is missing here is shown in English.
//  - names in <strong> are written as the control panel shows them, the words of Kleeja are in lang/ar/
//  - a <code> that begins or ends with a symbol has dir="ltr", or the symbol moves to the other side
//

return [
    'KJ_BAN_ADMIN_HELP_TITLE' => 'جدار حماية لوحة التحكم',
    'KJ_BAN_ADMIN_HELP_INTRO' =>
        'احمِ تسجيل الدخول إلى لوحة التحكم من تخمين كلمات المرور. يُحظر عنوان IP الذي يُدخل كلمة مرور خاطئة 10 مرات، فلا يعود بإمكانه فتح أي صفحة من موقعك.',

    //
    // what the plugin does
    //
    'KJ_BAN_ADMIN_HELP_FEATURE_1' =>
        'تحسب كلمات المرور الخاطئة التي تُدخل عند تسجيل الدخول إلى لوحة التحكم، لكل عنوان IP على حدة.',
    'KJ_BAN_ADMIN_HELP_FEATURE_2' =>
        'عند كلمة المرور الخاطئة العاشرة، تضيف العنوان إلى <strong>التحكم بالحظر</strong>، بجانب ما حظرته أنت بنفسك.',
    'KJ_BAN_ADMIN_HELP_FEATURE_3' =>
        'يُصفَّر عدّاد العنوان عند تسجيل دخول ناجح، وكذلك بعد مرور 24 ساعة دون كلمة مرور خاطئة.',
    'KJ_BAN_ADMIN_HELP_FEATURE_4' =>
        'لا تُحسب إلا كلمة المرور الخاطئة. أما الحقل الفارغ أو كود الأمان الخاطئ أو النموذج المنتهي فيوقف تسجيل الدخول قبل التحقق من كلمة المرور، فلا يُحسب.',

    //
    // how to lift a ban
    //
    'KJ_BAN_ADMIN_HELP_STEP_TITLE' => 'رفع الحظر',
    'KJ_BAN_ADMIN_HELP_STEP_1' =>
        'افتح <strong>التحكم بالحظر</strong> من عنوان غير محظور. وإن كان عنوانك أنت هو المحظور، فاستخدم شبكة أخرى، مثل بيانات الجوال في هاتفك.',
    'KJ_BAN_ADMIN_HELP_STEP_2' => 'ابحث عن عنوان IP المحظور في القائمة.',
    'KJ_BAN_ADMIN_HELP_STEP_3' =>
        'اضغط <strong>حذف</strong> بجانبه. يستطيع العنوان فتح موقعك من جديد على الفور، ويبدأ عدّاده من الصفر.',

    //
    // common questions
    //
    'KJ_BAN_ADMIN_HELP_FAQ_Q_1' => 'حُظر عنواني وليست لدي شبكة أخرى. كيف أستعيد الدخول؟',
    'KJ_BAN_ADMIN_HELP_FAQ_A_1' =>
        'عدّل قاعدة البيانات بأداة مثل phpMyAdmin. في جدول <code>stats</code> (واسمه <code>klj_stats</code> مع البادئة الافتراضية)، احذف عنوانك من عمود <code>ban</code> مع الفاصل <code>|</code> الذي يفصله عن غيره. ثم احذف الملف <code>cache/data_ban.php</code> ليقرأ كليجا القائمة من جديد.',
    'KJ_BAN_ADMIN_HELP_FAQ_Q_2' => 'أين أرى العناوين التي حظرتها الإضافة؟',
    'KJ_BAN_ADMIN_HELP_FAQ_A_2' =>
        'في <strong>التحكم بالحظر</strong>. لا تحتفظ الإضافة بقائمة خاصة بها، فحظرها وحظرك في المكان نفسه، وتُرفع بالطريقة نفسها.',
    'KJ_BAN_ADMIN_HELP_FAQ_Q_3' => 'هل تحمي الإضافة صفحة تسجيل الدخول في الموقع أيضًا؟',
    'KJ_BAN_ADMIN_HELP_FAQ_A_3' =>
        'لا. تراقب فقط تسجيل الدخول الذي تطلبه لوحة التحكم قبل أن تُفتح، ولا تُحسب كلمات المرور الخاطئة في صفحة تسجيل الدخول في الموقع.',
    'KJ_BAN_ADMIN_HELP_FAQ_Q_4' => 'ماذا يحدث للحظر عند حذف الإضافة؟',
    'KJ_BAN_ADMIN_HELP_FAQ_A_4' =>
        'يبقى في <strong>التحكم بالحظر</strong> حتى تحذفه أنت. ولا يُحذف مع الإضافة إلا عدّادات كلمات المرور الخاطئة.',

    //
    // beside the guide
    //
    'KJ_BAN_ADMIN_HELP_TIP_1' =>
        'تبطئ الإضافة تخمين كلمات المرور، لكنها لا تغني عن كلمة مرور قوية. استخدم كلمة طويلة لا تستخدمها في أي مكان آخر.',
    'KJ_BAN_ADMIN_HELP_TIP_2' =>
        'راجع <strong>التحكم بالحظر</strong> بين حين وآخر. فقد يتشارك من في شبكة المكتب نفسها أو شبكة الجوال نفسها عنوان IP واحدًا، والحظر يمنعهم جميعًا.',

    'KJ_BAN_ADMIN_HELP_WARNING_1' =>
        'الحظر يمنع العنوان من الموقع كله، ومن لوحة التحكم أيضًا. قبل الاعتماد على الإضافة، تأكد من أنك تستطيع الوصول إلى قاعدة البيانات أو إلى شبكة أخرى.',
    'KJ_BAN_ADMIN_HELP_WARNING_2' =>
        'إذا كان موقعك يعمل خلف بروكسي أو شبكة توزيع محتوى غير Cloudflare، فقد يرى كليجا كل الزوار بعنوان البروكسي، فيمنع حظرٌ واحد الجميع، وأنت منهم.',

    //
    // in the guide of Ban Control, whose list the plugin adds to
    //
    'KJ_BAN_ADMIN_HELP_NOTE' =>
        'تضيف إضافة <strong>جدار حماية لوحة التحكم</strong> عناوين IP إلى هذه القائمة تلقائيًا. <a href="#help-kj_ban_admin">اقرأ دليلها</a>.',
];
