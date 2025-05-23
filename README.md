# sommertop.ch - course material website

**Sommertop** is a Laravel application designed to bridge the gap between Dropbox and the sommertop.ch course website for the Swiss scouting movement. It allows course leaders to easily manage and publish course materials by simply organizing files and folders within a designated Dropbox directory. The application then dynamically reads this directory and presents its contents (files and subdirectories) as an accessible website for course participants.

## Purpose

The primary goal of Sommertop is to provide a user-friendly Content Management System (CMS) for Sommertop courses. Course leaders, who may not have extensive technical expertise, can update course materials (documents, images, schedules, etc.) by using the familiar interface of Dropbox. Participants can then access these materials directly on the `sommertop.ch` website.

## Key Features

* **Dropbox Integration:** Connects securely to a specific Dropbox directory.
* **Dynamic Content Display:** Reads the contents of the linked Dropbox folder (files and subfolders) and displays them on the website.
* **Easy Updates:** Course leaders manage website content by adding, removing, or modifying files in their Dropbox.
* **User-Friendly for Participants:** Provides a simple and intuitive way for participants to find and download course materials.
* **Built with Laravel:** A robust and modern PHP framework.

## Setup Instructions

Follow these steps to set up the Sommertop application:

### 1. Prerequisites

* Docker
* A Dropbox account

### 2. Clone the Repository
```bash
git clone https://github.com/carlobeltrame/sommertop
cd sommertop
```

### 3. Set up Dropbox Integration
1. Visit https://www.dropbox.com/developers/apps
1. Create a new app with scoped access and access to "Full Dropbox" (this is needed to expose a shared folder)
1. Give it a fitting name, e.g. sommertop Webseite
1. Leaving the app in developer mode is fine for now
1. If you are deploying the application: Add the URL https://sommertop.ch/f5 as Webhook URL (or adjust it to where you are hosting it)
1. On the "Permissions" tab, set the "account_info.read", "files.metadata.read" and "files.content.read" permissions, and submit using the link at the bottom of the screen
1. Back on the "Settings" tab, find your **App key** and **App secret**
1. Visit https://www.dropbox.com/oauth2/authorizeclient_id=<YOUR_APP_KEY>&response_type=code&token_access_type=offline and confirm that your app may access your Dropbox account. Get your temporary access code.
1. `curl https://api.dropbox.com/oauth2/token -d code=<ACCESS_CODE> -d grant_type=authorization_code -u <APP_KEY>:<APP_SECRET>` and inside the response get your **refresh token**.

### 4. Environment Configuration
Copy the example environment file and configure it:
```bash
cp .env.example .env
```
Open the `.env` file and update the following sections:

* `STORAGE_DIR="Sommertopkurs 2025/sommertop.ch"` (or any other directory you want to use in Dropbox)
* `DROPBOX_APP_KEY=` fill from above
* `DROPBOX_APP_SECRET=` fill from above
* `DROPBOX_REFRESH_TOKEN=` fill from above

### 5. Run using docker
```bash
docker compose up
```

Then, you can visit your local application at http://localhost

## Usage

### For Course Leaders

1.  **Organize Materials:** Create a main folder in your Dropbox that you've specified in the `.env` file as `STORAGE_DIR` (e.g., `Sommertopkurs 2025/sommertop.ch`).
2.  Inside this target directory, create subfolders for different sections or topics of your course.
3.  Upload files (PDFs, Word documents, images, presentations, etc.) into the relevant folders.
4.  To manually reorder the files and folders, prefix the filenames with 01_, 02_ and so on. The number prefix will not show on the website, but be used for ordering.
5.  To link to an external website, create a text file with the file extension `.link.txt` and put the link into this file.
6.  To include some text in a section, create a file with the file extension `.md` or `.html` and put the text into this file. In `.md` files, you can apply formatting such as **`**bold text**`**, _`_italic text_`_ or add enumeration lists.
7.  The Sommertop application will automatically reflect these changes on the `sommertop.ch` website.

### For Course Participants

1.  Visit the `sommertop.ch` website.
2.  Navigate through the displayed folder structure, which mirrors the structure in the course leaders' Dropbox.
3.  Click on files to view or download them.

## License

The Sommertop application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
