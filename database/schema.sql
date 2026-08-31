-- Personal Portfolio Website — Database Schema + Demo Seed Data
-- Import via phpMyAdmin or: c:\xampp\mysql\bin\mysql.exe -u root < schema.sql

CREATE DATABASE IF NOT EXISTS arafat_portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE arafat_portfolio;

-- ---------------------------------------------------------------
CREATE TABLE admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- default: username = admin , password = admin123  (CHANGE AFTER FIRST LOGIN)
INSERT INTO admin_users (username, password_hash) VALUES
('admin', '$2y$10$Ri3oSkn77Ph25ygIdyM5lu6gFBs99hSfe51yZ36UlOTlYhNO2wc4e');

-- ---------------------------------------------------------------
CREATE TABLE site_settings (
  setting_key VARCHAR(100) PRIMARY KEY,
  setting_value TEXT
) ENGINE=InnoDB;

INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_title', 'Arafat Hossain'),
('site_tagline', 'Full Stack Web Developer & UI/UX Enthusiast'),
('bio_short', 'I design and build fast, accessible, and beautiful web applications — from pixel-perfect interfaces to robust backend systems.'),
('bio_long', 'I am a passionate full-stack web developer with a love for turning complex problems into simple, elegant digital experiences. With hands-on experience across PHP, JavaScript, and modern front-end tooling, I focus on writing clean, maintainable code and crafting interfaces that people genuinely enjoy using. When I am not coding, I am usually exploring new design trends, contributing to open-source, or mentoring aspiring developers.'),
('email', 'contact@arafat.dev'),
('phone', '+880 1XXX-XXXXXX'),
('address', 'Dhaka, Bangladesh'),
('avatar', 'assets/images/avatar-placeholder.svg'),
('cv_path', 'uploads/cv/placeholder-cv.pdf'),
('social_github', 'https://github.com/'),
('social_linkedin', 'https://linkedin.com/'),
('social_facebook', 'https://facebook.com/'),
('social_twitter', 'https://twitter.com/'),
('social_instagram', 'https://instagram.com/'),
('footer_text', '© 2026 Arafat Hossain. All rights reserved.');

-- ---------------------------------------------------------------
CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  slug VARCHAR(160) NOT NULL UNIQUE,
  summary VARCHAR(300),
  description TEXT,
  cover_image VARCHAR(255),
  demo_url VARCHAR(255),
  github_url VARCHAR(255),
  technologies VARCHAR(255),
  category VARCHAR(100),
  featured TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO projects (title, slug, summary, description, cover_image, demo_url, github_url, technologies, category, featured) VALUES
('E-Commerce Storefront', 'ecommerce-storefront', 'A full-featured online store with cart, checkout, and admin inventory management.', 'A complete e-commerce platform built from scratch featuring product catalog, shopping cart, secure checkout flow, order tracking, and an admin dashboard for inventory and order management. Focused on performance and a smooth mobile shopping experience.', 'assets/images/project-placeholder-1.svg', 'https://example.com', 'https://github.com/', 'PHP, MySQL, JavaScript, CSS', 'Web App', 1),
('Task Management Dashboard', 'task-management-dashboard', 'A drag-and-drop kanban board for team task tracking with real-time updates.', 'A productivity tool inspired by Trello, allowing teams to organize tasks into boards, lists, and cards. Includes drag-and-drop reordering, due dates, labels, and team member assignment.', 'assets/images/project-placeholder-2.svg', 'https://example.com', 'https://github.com/', 'JavaScript, Node.js, MongoDB', 'Web App', 1),
('Restaurant Landing Page', 'restaurant-landing-page', 'A modern, responsive landing page for a fictional restaurant brand.', 'A visually rich single-page website featuring a hero banner, animated menu showcase, reservation form, and location map — designed to convert visitors into diners.', 'assets/images/project-placeholder-3.svg', 'https://example.com', 'https://github.com/', 'HTML, CSS, JavaScript', 'Landing Page', 1),
('Weather Forecast App', 'weather-forecast-app', 'A clean weather app with 7-day forecasts and location search.', 'Fetches live weather data and presents it through a minimal, card-based UI with animated weather icons and unit conversion (°C/°F).', 'assets/images/project-placeholder-4.svg', 'https://example.com', 'https://github.com/', 'JavaScript, REST API', 'Utility', 0),
('Personal Finance Tracker', 'personal-finance-tracker', 'Track income, expenses, and savings goals with visual charts.', 'A budgeting web app that helps users log transactions, categorize spending, and visualize monthly trends through interactive charts.', 'assets/images/project-placeholder-5.svg', 'https://example.com', 'https://github.com/', 'PHP, MySQL, Chart.js', 'Web App', 0),
('Portfolio CMS', 'portfolio-cms', 'A lightweight custom content management system for developer portfolios.', 'A no-framework PHP + MySQL admin panel that lets developers manage their portfolio content — projects, blog posts, and resume sections — without touching code.', 'assets/images/project-placeholder-6.svg', 'https://example.com', 'https://github.com/', 'PHP, MySQL', 'Tool', 0);

-- ---------------------------------------------------------------
CREATE TABLE blog_posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  excerpt VARCHAR(400),
  content LONGTEXT,
  cover_image VARCHAR(255),
  category VARCHAR(100),
  tags VARCHAR(255),
  published TINYINT(1) DEFAULT 1,
  views INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO blog_posts (title, slug, excerpt, content, cover_image, category, tags, published, views) VALUES
('Getting Started with PHP 8: What''s New', 'getting-started-php-8', 'A quick tour of the features that make PHP 8 a major step forward for backend developers.', '<p>PHP 8 introduced a number of powerful features including the JIT compiler, union types, named arguments, and match expressions. In this post we walk through the changes that matter most for everyday development.</p><p>The JIT compiler in particular opens the door to PHP being used in more performance-sensitive contexts, while named arguments make function calls far more readable when dealing with optional parameters.</p><p>Overall, upgrading to PHP 8 is a worthwhile investment for any project still running on older versions.</p>', 'assets/images/blog-placeholder-1.svg', 'Development', 'php, backend, tutorial', 1, 128),
('Designing for Dark Mode: A Practical Guide', 'designing-for-dark-mode', 'Tips and CSS techniques for building a dark mode that feels intentional, not bolted-on.', '<p>Dark mode is no longer a nice-to-have — users expect it. This guide covers how to structure your CSS custom properties so that switching themes is a single attribute change, not a rewrite.</p><p>We also cover contrast ratios, avoiding pure black backgrounds, and testing your palette for accessibility.</p>', 'assets/images/blog-placeholder-2.svg', 'Design', 'css, dark-mode, ui', 1, 94),
('Why I Switched to Vanilla JavaScript for Small Projects', 'why-vanilla-javascript', 'Frameworks are great, but sometimes plain JavaScript is the faster, lighter choice.', '<p>For small to medium projects, reaching for a full framework can add unnecessary overhead. This post shares my experience building fast, dependency-free interfaces with modern vanilla JavaScript APIs.</p>', 'assets/images/blog-placeholder-3.svg', 'Development', 'javascript, opinion', 1, 76),
('Building a Contact Form That Actually Stops Spam', 'contact-form-spam-prevention', 'Honeypots, CSRF tokens, and server-side validation — a practical checklist.', '<p>A public contact form is a magnet for bots. In this post I break down the layered approach I use: a hidden honeypot field, a CSRF token tied to the session, and strict server-side validation before anything touches the database.</p>', 'assets/images/blog-placeholder-4.svg', 'Security', 'php, security, forms', 1, 61);

-- ---------------------------------------------------------------
CREATE TABLE skills (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  category VARCHAR(100),
  proficiency INT DEFAULT 80,
  icon_key VARCHAR(50),
  sort_order INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO skills (name, category, proficiency, icon_key, sort_order) VALUES
('HTML5', 'Frontend', 95, 'code', 1),
('CSS3', 'Frontend', 92, 'code', 2),
('JavaScript', 'Frontend', 88, 'code', 3),
('PHP', 'Backend', 90, 'server', 4),
('MySQL', 'Backend', 85, 'database', 5),
('Node.js', 'Backend', 75, 'server', 6),
('Git & GitHub', 'Tools', 88, 'git', 7),
('UI/UX Design', 'Design', 80, 'palette', 8);

-- ---------------------------------------------------------------
CREATE TABLE education (
  id INT AUTO_INCREMENT PRIMARY KEY,
  degree VARCHAR(150) NOT NULL,
  institution VARCHAR(200),
  field VARCHAR(150),
  start_date VARCHAR(20),
  end_date VARCHAR(20),
  description TEXT,
  sort_order INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO education (degree, institution, field, start_date, end_date, description, sort_order) VALUES
('B.Sc. in Computer Science & Engineering', 'University of Dhaka', 'Computer Science', '2019', '2023', 'Focused on web technologies, database systems, and software engineering principles. Completed a final year project on real-time collaborative web applications.', 1),
('Higher Secondary Certificate (HSC)', 'Notre Dame College', 'Science', '2017', '2019', 'Concentration in Physics, Chemistry, and Higher Mathematics.', 2);

-- ---------------------------------------------------------------
CREATE TABLE experience (
  id INT AUTO_INCREMENT PRIMARY KEY,
  position VARCHAR(150) NOT NULL,
  company VARCHAR(200),
  location VARCHAR(150),
  start_date VARCHAR(20),
  end_date VARCHAR(20),
  is_current TINYINT(1) DEFAULT 0,
  description TEXT,
  sort_order INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO experience (position, company, location, start_date, end_date, is_current, description, sort_order) VALUES
('Full Stack Web Developer', 'Freelance', 'Remote', '2023', NULL, 1, 'Designing and building custom websites and web applications for clients across e-commerce, hospitality, and education sectors using PHP, MySQL, and modern JavaScript.', 1),
('Junior Web Developer Intern', 'TechNest Solutions', 'Dhaka, Bangladesh', '2022', '2023', 0, 'Assisted in developing and maintaining client websites, fixed cross-browser bugs, and contributed to an internal component library.', 2);

-- ---------------------------------------------------------------
CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  icon_key VARCHAR(50),
  sort_order INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO services (title, description, icon_key, sort_order) VALUES
('Web Development', 'Custom, responsive websites built with clean code and modern best practices — from landing pages to full web applications.', 'code', 1),
('UI/UX Design', 'User-centered interface design focused on clarity, accessibility, and conversion — from wireframes to polished prototypes.', 'palette', 2),
('Backend & Database Design', 'Robust PHP/MySQL backends, RESTful APIs, and well-structured database schemas built to scale.', 'database', 3),
('Website Maintenance', 'Ongoing support, performance optimization, and security updates to keep your website fast and reliable.', 'settings', 4);

-- ---------------------------------------------------------------
CREATE TABLE certificates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  issuer VARCHAR(200),
  issue_date VARCHAR(20),
  credential_url VARCHAR(255),
  image VARCHAR(255),
  sort_order INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO certificates (title, issuer, issue_date, credential_url, image, sort_order) VALUES
('Full Stack Web Development', 'freeCodeCamp', '2023', 'https://example.com', 'assets/images/cert-placeholder-1.svg', 1),
('Responsive Web Design', 'freeCodeCamp', '2022', 'https://example.com', 'assets/images/cert-placeholder-2.svg', 2),
('PHP & MySQL Bootcamp', 'Udemy', '2022', 'https://example.com', 'assets/images/cert-placeholder-3.svg', 3);

-- ---------------------------------------------------------------
CREATE TABLE faqs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question VARCHAR(255) NOT NULL,
  answer TEXT NOT NULL,
  sort_order INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO faqs (question, answer, sort_order) VALUES
('What services do you offer?', 'I offer web development, UI/UX design, backend and database design, and ongoing website maintenance. See the Services page for full details.', 1),
('What is your typical project timeline?', 'Timelines vary by scope — a landing page usually takes 1-2 weeks, while a full web application can take 4-8 weeks. I will give you a clear estimate after discussing your requirements.', 2),
('Do you work with clients remotely?', 'Yes, I work with clients both locally and remotely, communicating via email, video calls, and project management tools throughout the project.', 3),
('How can I get a quote for my project?', 'Just send a message through the Contact page with a brief description of your project, and I will get back to you with a quote and timeline.', 4),
('Do you provide post-launch support?', 'Yes, I offer maintenance and support packages after launch, including bug fixes, updates, and performance monitoring.', 5);

-- ---------------------------------------------------------------
CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(200),
  message TEXT NOT NULL,
  is_read TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
