## Cali Panel 20.0.1

This is the official repository for the new Cali Web Design Panel that will run virtually every modern business out there with a completely modulated experience.

---

### Features

- **Dashboard**: A comprehensive dashboard to view and manage all business operations.
- **CRM**: Customer Relationship Management tools to track and manage client interactions.
- **Analytics**: Powerful analytics tools to provide insights and reports.
- **Modular Design**: Customize the panel with various modules to fit your business needs.
- **Security**: State-of-the-art security features to protect your data.
- **Authentication**: Many different authentication types such as Google, Apple, Github and more.
- **Chat**: Ability to chat via text or call between teams and clients as well as communication tracking.
- **Web Design Tools and Web Host Tools**: Wether you are a Web Design or Hosting business the panel can do it all.
- **Customizability**: The panel supports themes and custom or prebuilt modules to allow customization to your hearts content, not to mention its open source.
- **Built for all businesses**: The panel can support any type of business from Accounting and Financial to Automotive to Web Design and Cloud Computing.
- **Payroll and Financial Services**: The panel does payroll, payments, financing, merchant processing and much more all from one place.
- **Much more coming soon**: We plan to add tons of great features between now and release.

---

### Technologies Used

- **PHP** (with Composer)
- **MySQL**
- **Linux**
- **NGINX**
- **HTML**
- **CSS**
- **JavaScript**
- **Pre done ENV Files**

---

### Authors

- Nick Derry
- Mikey Brinkley
- Aiden Webb
- Nathan Schwartz
- Mikey W

---

### Getting Started

This panel is still in development so the install script has not been built yet. The panel also is buggy and unfinished
it currently is only open to Developer Testing.

You can view a demo link [here](https://us-east.cali-cloud-compute-135-148-28-43.caliwebdesignservices.com/).

---

### Prerequisites

- PHP
- Composer
- MySQL
- Git
- Linux
- NGINX

---

### Installation

1. Clone the repository: `bash git clone https://github.com/NickDerry2023/CaliWebDesignPanel20.0.1.git`
2. Install the panel by running the install.sh bash script.
3. Run post installation by navigating to the panels domain then the folder /install
4. Configure the panel and set credentials in the .ENV file.
5. Run the cron jobs by doing: `crontab -e` and `0 * * * * /usr/bin/php /var/www/calipanel/automations/fileDeletion/index.php`
6. Login to the admin account you created.