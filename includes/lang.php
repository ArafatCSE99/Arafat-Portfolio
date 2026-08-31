<?php
/**
 * Bilingual (English / Bangla) support.
 * lang()   -> current language code: 'en' | 'bn'
 * t($key)  -> static UI string translation (nav, buttons, labels, headings)
 * tf($row, $field) -> DB-driven content translation; falls back to the
 *                      English column if no Bangla value has been entered.
 */

/**
 * Bilingual switching is currently disabled (site is English-only) —
 * this always returns 'en' so every t()/tf() call resolves to English
 * without needing to touch every page that already calls them.
 */
function lang(): string
{
    return 'en';
}

/** Builds a link to the current page with the given language applied. */
function lang_url(string $code): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $parts = parse_url($uri);
    parse_str($parts['query'] ?? '', $query);
    $query['lang'] = $code;
    $path = $parts['path'] ?? '/';
    return $path . '?' . http_build_query($query);
}

function tf(array $row, string $field): string
{
    if (lang() === 'bn' && !empty($row[$field . '_bn'])) {
        return $row[$field . '_bn'];
    }
    return $row[$field] ?? '';
}

const LANG_STRINGS = [
    // Nav / footer
    'nav.home' => ['en' => 'Home', 'bn' => 'হোম'],
    'nav.about' => ['en' => 'About', 'bn' => 'পরিচিতি'],
    'nav.services' => ['en' => 'Services', 'bn' => 'সেবাসমূহ'],
    'nav.projects' => ['en' => 'Projects', 'bn' => 'প্রজেক্ট'],
    'nav.blog' => ['en' => 'Blog', 'bn' => 'ব্লগ'],
    'nav.tools' => ['en' => 'Tools', 'bn' => 'টুলস'],
    'nav.faq' => ['en' => 'FAQ', 'bn' => 'প্রশ্নোত্তর'],
    'nav.contact' => ['en' => 'Contact', 'bn' => 'যোগাযোগ'],
    'footer.quick_links' => ['en' => 'Quick Links', 'bn' => 'দ্রুত লিংক'],
    'footer.about_me' => ['en' => 'About Me', 'bn' => 'আমার সম্পর্কে'],
    'footer.free_tools' => ['en' => 'Free Tools', 'bn' => 'ফ্রি টুলস'],
    'footer.get_in_touch' => ['en' => 'Get in Touch', 'bn' => 'যোগাযোগ করুন'],
    'footer.contact_form' => ['en' => 'Contact Form →', 'bn' => 'যোগাযোগ ফর্ম →'],
    'footer.built_with' => ['en' => 'Built with PHP & MySQL', 'bn' => 'PHP ও MySQL দিয়ে তৈরি'],
    'footer.back_to_top' => ['en' => 'Back to top', 'bn' => 'উপরে ফিরুন'],

    // Intro splash
    'intro.title' => ['en' => "Welcome to Arafat's Virtual Gallery", 'bn' => 'আরাফাতের ভার্চুয়াল গ্যালারিতে স্বাগতম'],
    'intro.subtitle' => ['en' => 'Crafting clean, functional web experiences', 'bn' => 'পরিচ্ছন্ন ও কার্যকর ওয়েব অভিজ্ঞতা তৈরি করি'],

    // Home
    'home.hero_eyebrow' => ['en' => 'Welcome to my portfolio', 'bn' => 'আমার পোর্টফোলিওতে স্বাগতম'],
    'home.hero_greeting' => ['en' => "Hi, I'm", 'bn' => 'হ্যালো, আমি'],
    'home.view_work' => ['en' => 'View My Work', 'bn' => 'আমার কাজ দেখুন'],
    'home.download_cv' => ['en' => 'Download CV', 'bn' => 'সিভি ডাউনলোড করুন'],
    'home.experience_badge' => ['en' => '2+ Years Experience', 'bn' => '২+ বছরের অভিজ্ঞতা'],
    'home.about_eyebrow' => ['en' => 'About Me', 'bn' => 'আমার সম্পর্কে'],
    'home.about_title' => ['en' => 'A little about who I am', 'bn' => 'আমার সম্পর্কে সংক্ষেপে'],
    'home.about_btn' => ['en' => 'More About Me', 'bn' => 'আরও জানুন'],
    'home.skills_eyebrow' => ['en' => 'What I Know', 'bn' => 'আমার দক্ষতা'],
    'home.skills_title' => ['en' => 'Skills & Technologies', 'bn' => 'স্কিল ও প্রযুক্তি'],
    'home.skills_desc' => ['en' => 'Tools and technologies I use to bring ideas to life.', 'bn' => 'যেসব টুল ও প্রযুক্তি দিয়ে আমি ধারণাকে বাস্তবে রূপ দিই।'],
    'home.projects_eyebrow' => ['en' => 'Portfolio', 'bn' => 'পোর্টফোলিও'],
    'home.projects_title' => ['en' => 'Featured Projects', 'bn' => 'বিশেষ প্রজেক্টসমূহ'],
    'home.projects_desc' => ['en' => "A selection of things I've recently built.", 'bn' => 'সম্প্রতি তৈরি করা কিছু কাজের নমুনা।'],
    'home.see_all_projects' => ['en' => 'See All Projects', 'bn' => 'সব প্রজেক্ট দেখুন'],
    'home.services_eyebrow' => ['en' => 'What I Offer', 'bn' => 'আমি যা প্রদান করি'],
    'home.services_title' => ['en' => 'Services', 'bn' => 'সেবাসমূহ'],
    'home.blog_eyebrow' => ['en' => 'From the Blog', 'bn' => 'ব্লগ থেকে'],
    'home.blog_title' => ['en' => 'Latest Articles', 'bn' => 'সাম্প্রতিক লেখা'],
    'home.blog_desc' => ['en' => 'Thoughts on development, design, and everything in between.', 'bn' => 'ডেভেলপমেন্ট, ডিজাইন এবং আরও অনেক কিছু নিয়ে ভাবনা।'],
    'home.cta_title' => ['en' => "Let's build something great together", 'bn' => 'চলুন একসাথে দারুণ কিছু তৈরি করি'],
    'home.cta_desc' => ['en' => "Have a project in mind or just want to say hello? I'd love to hear from you.", 'bn' => 'কোনো প্রজেক্ট নিয়ে ভাবছেন বা শুধু কথা বলতে চান? আমি শুনতে আগ্রহী।'],
    'home.cta_btn' => ['en' => 'Get In Touch', 'bn' => 'যোগাযোগ করুন'],

    // Shared
    'common.view_details' => ['en' => 'View Details', 'bn' => 'বিস্তারিত দেখুন'],
    'common.read_article' => ['en' => 'Read Article', 'bn' => 'পড়ুন'],
    'common.open_tool' => ['en' => 'Open Tool', 'bn' => 'টুল খুলুন'],
    'common.email' => ['en' => 'Email', 'bn' => 'ইমেইল'],
    'common.phone' => ['en' => 'Phone', 'bn' => 'ফোন'],
    'common.location' => ['en' => 'Location', 'bn' => 'ঠিকানা'],
    'common.availability' => ['en' => 'Availability', 'bn' => 'উপলব্ধতা'],
    'common.open_to_work' => ['en' => 'Open to work', 'bn' => 'কাজের জন্য উন্মুক্ত'],
    'common.present' => ['en' => 'Present', 'bn' => 'বর্তমান'],
    'common.back_home' => ['en' => 'Home', 'bn' => 'হোম'],

    // About page
    'about.eyebrow' => ['en' => 'Get To Know Me', 'bn' => 'আমাকে জানুন'],
    'about.heading' => ['en' => 'About Me', 'bn' => 'আমার সম্পর্কে'],
    'about.who_eyebrow' => ['en' => 'Who I Am', 'bn' => 'আমি যেমন'],
    'about.skills_eyebrow' => ['en' => 'Skills', 'bn' => 'দক্ষতা'],
    'about.skills_title' => ['en' => 'My Skill Set', 'bn' => 'আমার স্কিল সেট'],
    'about.education_eyebrow' => ['en' => 'Education', 'bn' => 'শিক্ষাগত যোগ্যতা'],
    'about.education_title' => ['en' => 'Academic Background', 'bn' => 'শিক্ষাগত পটভূমি'],
    'about.experience_eyebrow' => ['en' => 'Experience', 'bn' => 'অভিজ্ঞতা'],
    'about.experience_title' => ['en' => 'Work Experience', 'bn' => 'কর্ম অভিজ্ঞতা'],
    'about.certificates_eyebrow' => ['en' => 'Certificates', 'bn' => 'সার্টিফিকেট'],
    'about.certificates_title' => ['en' => 'Certifications & Achievements', 'bn' => 'সার্টিফিকেট ও অর্জন'],

    // Services page
    'services.heading' => ['en' => 'My Services', 'bn' => 'আমার সেবাসমূহ'],
    'services.desc' => ['en' => "Here's how I can help turn your idea into a polished digital product.", 'bn' => 'আপনার আইডিয়াকে একটি নিখুঁত ডিজিটাল প্রোডাক্টে রূপ দিতে আমি এভাবে সাহায্য করতে পারি।'],
    'services.custom_title' => ['en' => 'Need something custom?', 'bn' => 'কাস্টম কিছু দরকার?'],
    'services.custom_desc' => ['en' => "Every project is different. Tell me about yours and let's figure out the best way to bring it to life.", 'bn' => 'প্রতিটি প্রজেক্ট আলাদা। আপনারটা সম্পর্কে জানান, একসাথে সেরা উপায় খুঁজে বের করি।'],
    'services.custom_btn' => ['en' => 'Start a Conversation', 'bn' => 'কথা শুরু করুন'],

    // Projects
    'projects.heading' => ['en' => 'My Projects', 'bn' => 'আমার প্রজেক্টসমূহ'],
    'projects.desc' => ['en' => "A selection of web applications, tools, and websites I've designed and built.", 'bn' => 'আমার ডিজাইন করা ও তৈরি করা কিছু ওয়েব অ্যাপ্লিকেশন, টুল ও ওয়েবসাইট।'],
    'projects.filter_all' => ['en' => 'All', 'bn' => 'সব'],
    'projects.empty' => ['en' => 'No projects yet — check back soon!', 'bn' => 'এখনো কোনো প্রজেক্ট নেই — শীঘ্রই দেখুন!'],
    'projects.not_found' => ['en' => 'Sorry, this project could not be found.', 'bn' => 'দুঃখিত, এই প্রজেক্টটি পাওয়া যায়নি।'],
    'projects.back' => ['en' => 'Back to Projects', 'bn' => 'প্রজেক্টে ফিরে যান'],
    'projects.overview' => ['en' => 'Project Overview', 'bn' => 'প্রজেক্ট বিবরণ'],
    'projects.info' => ['en' => 'Project Info', 'bn' => 'প্রজেক্ট তথ্য'],
    'projects.technologies' => ['en' => 'Technologies', 'bn' => 'প্রযুক্তি'],
    'projects.live_demo' => ['en' => 'Live Demo', 'bn' => 'লাইভ ডেমো'],
    'projects.source_code' => ['en' => 'Source Code', 'bn' => 'সোর্স কোড'],
    'projects.related' => ['en' => 'Related Projects', 'bn' => 'সম্পর্কিত প্রজেক্ট'],

    // Blog
    'blog.heading' => ['en' => 'Articles & Tutorials', 'bn' => 'আর্টিকেল ও টিউটোরিয়াল'],
    'blog.desc' => ['en' => 'Thoughts on development, design, and everything in between.', 'bn' => 'ডেভেলপমেন্ট, ডিজাইন এবং আরও অনেক কিছু নিয়ে ভাবনা।'],
    'blog.search_placeholder' => ['en' => 'Search articles...', 'bn' => 'আর্টিকেল খুঁজুন...'],
    'blog.empty' => ['en' => 'No articles published yet — check back soon!', 'bn' => 'এখনো কোনো আর্টিকেল প্রকাশিত হয়নি — শীঘ্রই দেখুন!'],
    'blog.not_found' => ['en' => 'Sorry, this article could not be found.', 'bn' => 'দুঃখিত, এই আর্টিকেলটি পাওয়া যায়নি।'],
    'blog.back' => ['en' => 'Back to Blog', 'bn' => 'ব্লগে ফিরে যান'],
    'blog.views' => ['en' => 'views', 'bn' => 'ভিউ'],
    'blog.related' => ['en' => 'You Might Also Like', 'bn' => 'আপনার আরও ভালো লাগতে পারে'],
    'blog.read_in_bangla' => ['en' => 'বাংলায় পড়ুন', 'bn' => 'বাংলায় পড়ুন'],
    'blog.read_in_english' => ['en' => 'Read in English', 'bn' => 'Read in English'],

    // Contact
    'contact.eyebrow' => ['en' => 'Get In Touch', 'bn' => 'যোগাযোগ করুন'],
    'contact.heading' => ['en' => 'Contact Me', 'bn' => 'আমার সাথে যোগাযোগ করুন'],
    'contact.desc' => ['en' => 'Have a question or want to work together? Send me a message.', 'bn' => 'কোনো প্রশ্ন আছে বা একসাথে কাজ করতে চান? আমাকে মেসেজ পাঠান।'],
    'contact.name' => ['en' => 'Full Name', 'bn' => 'পূর্ণ নাম'],
    'contact.email' => ['en' => 'Email Address', 'bn' => 'ইমেইল ঠিকানা'],
    'contact.subject' => ['en' => 'Subject', 'bn' => 'বিষয়'],
    'contact.message' => ['en' => 'Message', 'bn' => 'বার্তা'],
    'contact.send' => ['en' => 'Send Message', 'bn' => 'বার্তা পাঠান'],
    'contact.success' => ['en' => "Thanks! Your message has been sent successfully — I'll get back to you soon.", 'bn' => 'ধন্যবাদ! আপনার বার্তা সফলভাবে পাঠানো হয়েছে — শীঘ্রই যোগাযোগ করব।'],
    'contact.err_session' => ['en' => 'Your session expired. Please try submitting the form again.', 'bn' => 'আপনার সেশন মেয়াদ শেষ হয়ে গেছে। আবার চেষ্টা করুন।'],
    'contact.err_name' => ['en' => 'Please enter a valid name.', 'bn' => 'দয়া করে সঠিক নাম দিন।'],
    'contact.err_email' => ['en' => 'Please enter a valid email address.', 'bn' => 'দয়া করে সঠিক ইমেইল ঠিকানা দিন।'],
    'contact.err_message' => ['en' => 'Please enter a message (max 5000 characters).', 'bn' => 'দয়া করে একটি বার্তা লিখুন (সর্বোচ্চ ৫০০০ অক্ষর)।'],

    // FAQ
    'faq.eyebrow' => ['en' => 'Have Questions?', 'bn' => 'কোনো প্রশ্ন আছে?'],
    'faq.heading' => ['en' => 'Frequently Asked Questions', 'bn' => 'সচরাচর জিজ্ঞাসিত প্রশ্ন'],
    'faq.desc_prefix' => ['en' => 'Answers to the questions I get asked most often. Still curious about something?', 'bn' => 'সবচেয়ে বেশি জিজ্ঞাসিত প্রশ্নের উত্তর। আরও কিছু জানতে চান?'],
    'faq.contact_link' => ['en' => 'Get in touch', 'bn' => 'যোগাযোগ করুন'],
    'faq.empty' => ['en' => 'No questions added yet.', 'bn' => 'এখনো কোনো প্রশ্ন যোগ করা হয়নি।'],

    // Tools hub
    'tools.eyebrow' => ['en' => 'Free & Instant', 'bn' => 'ফ্রি ও তাৎক্ষণিক'],
    'tools.heading' => ['en' => 'Useful Tools', 'bn' => 'দরকারি টুলস'],
    'tools.desc' => ['en' => 'A handful of small utilities I built — free to use, right in your browser.', 'bn' => 'কিছু ছোট দরকারি টুল — ব্রাউজারেই বিনামূল্যে ব্যবহার করুন।'],
    'tools.tag' => ['en' => 'Tool', 'bn' => 'টুল'],

    'tool.age.title' => ['en' => 'Age Calculator', 'bn' => 'বয়স ক্যালকুলেটর'],
    'tool.age.desc' => ['en' => 'Find your exact age in years, months, and days.', 'bn' => 'আপনার সঠিক বয়স বছর, মাস ও দিনে বের করুন।'],
    'tool.age.sub_desc' => ['en' => 'Enter your date of birth to find your exact age.', 'bn' => 'সঠিক বয়স জানতে জন্ম তারিখ দিন।'],
    'tool.age.label' => ['en' => 'Date of Birth', 'bn' => 'জন্ম তারিখ'],
    'tool.age.btn' => ['en' => 'Calculate Age', 'bn' => 'বয়স হিসাব করুন'],
    'tool.age.years' => ['en' => 'Years', 'bn' => 'বছর'],
    'tool.age.months' => ['en' => 'Months', 'bn' => 'মাস'],
    'tool.age.days' => ['en' => 'Days', 'bn' => 'দিন'],

    'tool.percentage.title' => ['en' => 'Percentage Calculator', 'bn' => 'শতাংশ ক্যালকুলেটর'],
    'tool.percentage.desc' => ['en' => 'Quickly calculate percentages, increases, and decreases.', 'bn' => 'দ্রুত শতাংশ, বৃদ্ধি ও হ্রাস হিসাব করুন।'],
    'tool.percentage.sub_desc' => ['en' => 'Three quick calculators for everyday percentage problems.', 'bn' => 'দৈনন্দিন শতাংশ সমস্যার জন্য তিনটি দ্রুত ক্যালকুলেটর।'],
    'tool.percentage.calc' => ['en' => 'Calculate', 'bn' => 'হিসাব করুন'],
    'tool.percentage.q1' => ['en' => 'What is X% of Y?', 'bn' => 'X% অফ Y কত?'],
    'tool.percentage.q2' => ['en' => 'X is what percent of Y?', 'bn' => 'X, Y-এর কত শতাংশ?'],
    'tool.percentage.q3' => ['en' => 'Percentage change from X to Y', 'bn' => 'X থেকে Y পর্যন্ত শতাংশ পরিবর্তন'],
    'tool.percentage.from' => ['en' => 'From (X)', 'bn' => 'থেকে (X)'],
    'tool.percentage.to' => ['en' => 'To (Y)', 'bn' => 'পর্যন্ত (Y)'],

    'tool.word.title' => ['en' => 'Word Counter', 'bn' => 'শব্দ গণনা'],
    'tool.word.desc' => ['en' => 'Count words, characters, sentences, and reading time.', 'bn' => 'শব্দ, অক্ষর, বাক্য ও পড়ার সময় গণনা করুন।'],
    'tool.word.sub_desc' => ['en' => 'Paste or type your text below to see live stats.', 'bn' => 'নিচে টেক্সট লিখুন বা পেস্ট করুন, তাৎক্ষণিক ফলাফল দেখুন।'],
    'tool.word.your_text' => ['en' => 'Your Text', 'bn' => 'আপনার টেক্সট'],
    'tool.word.placeholder' => ['en' => 'Start typing or paste your text here...', 'bn' => 'এখানে লিখুন বা পেস্ট করুন...'],
    'tool.word.words' => ['en' => 'Words', 'bn' => 'শব্দ'],
    'tool.word.characters' => ['en' => 'Characters', 'bn' => 'অক্ষর'],
    'tool.word.sentences' => ['en' => 'Sentences', 'bn' => 'বাক্য'],
    'tool.word.reading_time' => ['en' => 'Reading Time', 'bn' => 'পড়ার সময়'],

    'tool.qr.title' => ['en' => 'QR Code Generator', 'bn' => 'QR কোড জেনারেটর'],
    'tool.qr.desc' => ['en' => 'Generate a downloadable QR code from any text or URL.', 'bn' => 'যেকোনো টেক্সট বা URL থেকে ডাউনলোডযোগ্য QR কোড তৈরি করুন।'],
    'tool.qr.sub_desc' => ['en' => 'Enter any text or URL to instantly generate a downloadable QR code.', 'bn' => 'তাৎক্ষণিক QR কোড তৈরি করতে যেকোনো টেক্সট বা URL দিন।'],
    'tool.qr.label' => ['en' => 'Text or URL', 'bn' => 'টেক্সট বা URL'],
    'tool.qr.btn' => ['en' => 'Generate QR Code', 'bn' => 'QR কোড তৈরি করুন'],
    'tool.qr.download' => ['en' => 'Download PNG', 'bn' => 'PNG ডাউনলোড করুন'],

    'tool.password.title' => ['en' => 'Password Generator', 'bn' => 'পাসওয়ার্ড জেনারেটর'],
    'tool.password.desc' => ['en' => 'Create strong, random, and secure passwords instantly.', 'bn' => 'শক্তিশালী ও নিরাপদ পাসওয়ার্ড তাৎক্ষণিক তৈরি করুন।'],
    'tool.password.sub_desc' => ['en' => 'Create a strong, random password with the options below.', 'bn' => 'নিচের অপশন ব্যবহার করে শক্তিশালী পাসওয়ার্ড তৈরি করুন।'],
    'tool.password.copy' => ['en' => 'Copy', 'bn' => 'কপি করুন'],
    'tool.password.length' => ['en' => 'Length', 'bn' => 'দৈর্ঘ্য'],
    'tool.password.uppercase' => ['en' => 'Uppercase Letters (A-Z)', 'bn' => 'বড় হাতের অক্ষর (A-Z)'],
    'tool.password.lowercase' => ['en' => 'Lowercase Letters (a-z)', 'bn' => 'ছোট হাতের অক্ষর (a-z)'],
    'tool.password.numbers' => ['en' => 'Numbers (0-9)', 'bn' => 'সংখ্যা (0-9)'],
    'tool.password.symbols' => ['en' => 'Symbols (!@#$...)', 'bn' => 'চিহ্ন (!@#$...)'],
    'tool.password.generate' => ['en' => 'Generate Password', 'bn' => 'পাসওয়ার্ড তৈরি করুন'],

    'tool.bmi.title' => ['en' => 'BMI Calculator', 'bn' => 'বিএমআই ক্যালকুলেটর'],
    'tool.bmi.desc' => ['en' => 'Calculate your Body Mass Index and check your healthy range.', 'bn' => 'আপনার বডি মাস ইনডেক্স হিসাব করুন।'],
    'tool.bmi.sub_desc' => ['en' => 'Calculate your Body Mass Index (BMI) from your height and weight.', 'bn' => 'উচ্চতা ও ওজন থেকে বিএমআই হিসাব করুন।'],
    'tool.bmi.height' => ['en' => 'Height (cm)', 'bn' => 'উচ্চতা (সেমি)'],
    'tool.bmi.weight' => ['en' => 'Weight (kg)', 'bn' => 'ওজন (কেজি)'],
    'tool.bmi.btn' => ['en' => 'Calculate BMI', 'bn' => 'বিএমআই হিসাব করুন'],

    'tool.unit.title' => ['en' => 'Unit Converter', 'bn' => 'একক রূপান্তরকারী'],
    'tool.unit.desc' => ['en' => 'Convert between length, weight, and temperature units.', 'bn' => 'দৈর্ঘ্য, ওজন ও তাপমাত্রার একক রূপান্তর করুন।'],
    'tool.unit.sub_desc' => ['en' => 'Convert between common length, weight, and temperature units.', 'bn' => 'সাধারণ দৈর্ঘ্য, ওজন ও তাপমাত্রার একক রূপান্তর করুন।'],
    'tool.unit.category' => ['en' => 'Category', 'bn' => 'ক্যাটাগরি'],
    'tool.unit.from' => ['en' => 'From', 'bn' => 'থেকে'],
    'tool.unit.to' => ['en' => 'To', 'bn' => 'তে'],
    'tool.unit.value' => ['en' => 'Value', 'bn' => 'মান'],

    'tool.color.title' => ['en' => 'Color Picker', 'bn' => 'কালার পিকার'],
    'tool.color.desc' => ['en' => "Pick a color and get its HEX, RGB, and HSL values.", 'bn' => 'একটি রং বেছে নিন, HEX, RGB ও HSL মান পান।'],
    'tool.color.sub_desc' => ['en' => 'Pick a color to get its HEX, RGB, and HSL values.', 'bn' => 'রং বেছে নিয়ে এর মান দেখুন।'],
    'tool.color.pick' => ['en' => 'Pick a Color', 'bn' => 'রং বেছে নিন'],

    'tool.json.title' => ['en' => 'JSON Formatter', 'bn' => 'JSON ফরম্যাটার'],
    'tool.json.desc' => ['en' => 'Format, validate, and beautify raw JSON data.', 'bn' => 'JSON ডেটা ফরম্যাট ও যাচাই করুন।'],
    'tool.json.sub_desc' => ['en' => 'Paste raw JSON below to validate and beautify it.', 'bn' => 'নিচে JSON পেস্ট করে ফরম্যাট ও যাচাই করুন।'],
    'tool.json.label' => ['en' => 'Raw JSON', 'bn' => 'JSON ডেটা'],
    'tool.json.format' => ['en' => 'Format & Validate', 'bn' => 'ফরম্যাট ও যাচাই করুন'],
    'tool.json.minify' => ['en' => 'Minify', 'bn' => 'সংক্ষেপ করুন'],
    'tool.json.clear' => ['en' => 'Clear', 'bn' => 'মুছুন'],

    'tool.cvgen.title' => ['en' => 'CV / Resume Generator', 'bn' => 'সিভি জেনারেটর'],
    'tool.cvgen.desc' => ['en' => 'Build a professional, animated resume live and download it as a PDF.', 'bn' => 'লাইভ প্রফেশনাল সিভি তৈরি করুন এবং PDF ডাউনলোড করুন।'],
    'tool.cvgen.sub_desc' => ['en' => 'Fill in the form — your resume updates live on the right. Add a photo, pick an accent color, then download the PDF.', 'bn' => 'ফর্ম পূরণ করুন — ডানপাশে সিভি সাথে সাথে আপডেট হবে। ছবি যোগ করুন, রং বেছে নিন, তারপর PDF ডাউনলোড করুন।'],
    'tool.cvgen.section_personal' => ['en' => 'Personal Info', 'bn' => 'ব্যক্তিগত তথ্য'],
    'tool.cvgen.photo' => ['en' => 'Photo', 'bn' => 'ছবি'],
    'tool.cvgen.photo_upload' => ['en' => 'Upload Photo', 'bn' => 'ছবি আপলোড করুন'],
    'tool.cvgen.photo_remove' => ['en' => 'Remove', 'bn' => 'মুছুন'],
    'tool.cvgen.full_name' => ['en' => 'Full Name', 'bn' => 'পুরো নাম'],
    'tool.cvgen.job_title' => ['en' => 'Job Title / Tagline', 'bn' => 'পদবি / ট্যাগলাইন'],
    'tool.cvgen.email' => ['en' => 'Email', 'bn' => 'ইমেইল'],
    'tool.cvgen.phone' => ['en' => 'Phone', 'bn' => 'ফোন'],
    'tool.cvgen.address' => ['en' => 'Address', 'bn' => 'ঠিকানা'],
    'tool.cvgen.summary' => ['en' => 'Professional Summary', 'bn' => 'সংক্ষিপ্ত পরিচিতি'],
    'tool.cvgen.section_experience' => ['en' => 'Work Experience', 'bn' => 'কর্ম অভিজ্ঞতা'],
    'tool.cvgen.add_experience' => ['en' => 'Add Experience', 'bn' => 'অভিজ্ঞতা যোগ করুন'],
    'tool.cvgen.position' => ['en' => 'Position', 'bn' => 'পদবি'],
    'tool.cvgen.company' => ['en' => 'Company', 'bn' => 'প্রতিষ্ঠান'],
    'tool.cvgen.start' => ['en' => 'Start', 'bn' => 'শুরু'],
    'tool.cvgen.end' => ['en' => 'End', 'bn' => 'শেষ'],
    'tool.cvgen.current' => ['en' => 'Present', 'bn' => 'বর্তমান'],
    'tool.cvgen.description' => ['en' => 'Description', 'bn' => 'বিবরণ'],
    'tool.cvgen.section_education' => ['en' => 'Education', 'bn' => 'শিক্ষাগত যোগ্যতা'],
    'tool.cvgen.add_education' => ['en' => 'Add Education', 'bn' => 'শিক্ষা যোগ করুন'],
    'tool.cvgen.degree' => ['en' => 'Degree', 'bn' => 'ডিগ্রি'],
    'tool.cvgen.institution' => ['en' => 'Institution', 'bn' => 'প্রতিষ্ঠান'],
    'tool.cvgen.section_skills' => ['en' => 'Skills', 'bn' => 'দক্ষতা'],
    'tool.cvgen.add_skill' => ['en' => 'Add Skill', 'bn' => 'দক্ষতা যোগ করুন'],
    'tool.cvgen.skill_name' => ['en' => 'Skill', 'bn' => 'দক্ষতা'],
    'tool.cvgen.section_certificates' => ['en' => 'Certificates', 'bn' => 'সার্টিফিকেট'],
    'tool.cvgen.add_cert' => ['en' => 'Add Certificate', 'bn' => 'সার্টিফিকেট যোগ করুন'],
    'tool.cvgen.cert_title' => ['en' => 'Title', 'bn' => 'শিরোনাম'],
    'tool.cvgen.issuer' => ['en' => 'Issuer', 'bn' => 'প্রদানকারী'],
    'tool.cvgen.year' => ['en' => 'Year', 'bn' => 'সাল'],
    'tool.cvgen.theme_color' => ['en' => 'Accent Color', 'bn' => 'থিম রং'],
    'tool.cvgen.download' => ['en' => 'Download PDF', 'bn' => 'PDF ডাউনলোড করুন'],
    'tool.cvgen.reset' => ['en' => 'Reset', 'bn' => 'রিসেট করুন'],
    'tool.cvgen.remove' => ['en' => 'Remove', 'bn' => 'সরান'],
    'tool.cvgen.preview' => ['en' => 'Live Preview', 'bn' => 'লাইভ প্রিভিউ'],
    'tool.cvgen.privacy_note' => ['en' => 'Everything happens in your browser — nothing is uploaded to any server.', 'bn' => 'সবকিছু আপনার ব্রাউজারেই হয় — কোনো সার্ভারে আপলোড হয় না।'],
];

function t(string $key): string
{
    $entry = LANG_STRINGS[$key] ?? null;
    if (!$entry) {
        return $key;
    }
    return $entry[lang()] ?? $entry['en'];
}
