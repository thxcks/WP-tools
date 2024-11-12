<!-- Improved compatibility of back to top link: See: https://github.com/othneildrew/Best-README-Template/pull/73 -->
<a id="readme-top"></a>


<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/thxcks/WP-tools">
    <img src="https://i.ibb.co/SXjCbVw/wp-toolkits-nobg.png" alt="Logo" width="150" height="150">
  </a>

<h3 align="center">Wordpress Shared Hosting Self Help Tools</h3>

  <p align="center">
A collection of tools designed to help users independently manage and troubleshoot common WordPress issues on shared hosting environments. Built to address typical WordPress tasks without the need for plugins, these tools optimize performance and avoid the bloat, slowdowns, and security risks often introduced by plugin-based solutions. This toolset empowers users to manage their sites efficiently, maintaining a lean, secure WordPress installation without relying on direct hosting provider intervention.  </p>
</div>



<!-- GETTING STARTED -->
## Getting Started

Most scripts are designed to run directly in your WordPress installation's document root. Simply upload the file, then access it via your browser to begin using its functionality.

Each script includes a simple login setup: on the first run, you’ll set a password, which you’ll use for future access to the tool.

### List of Scripts and Usage

In the example, we use `wget` to download the tools, but you’re free to download and upload them using any method that suits your workflow.

<h2>Plugin Tester</h2>
<p>Disables each plugin in your installation one by one until the plugin conflict is resolved.</p>

  ```sh
  wget https://raw.githubusercontent.com/thxcks/Public/refs/heads/main/php/plugin-tester.php
  ```

<h2>File/Folder Permission Manager</h2>
<p>Easily browse your file structure and set folder permissions to 755 and file permissions to 644 in bulk.</p>

  ```sh
  wget https://raw.githubusercontent.com/thxcks/Public/refs/heads/main/php/plugin-tester.php
  ```

<h2>MYSQL Process Viewer</h2>
<p>View real-time MySQL processes for your WordPress database with automatic refresh and control options.</p>

  ```sh
  wget https://raw.githubusercontent.com/thxcks/Public/refs/heads/main/php/plugin-tester.php
  ```


<p align="right">(<a href="#readme-top">back to top</a>)</p>


<!-- ROADMAP -->
## Tool Roadmap

- [ ] Reinstall Core WP
- [ ] Backup/Restore
- [ ] Locate and manage multiple WP Insalls
- [ ] Many More


<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, simply open an issue with the tag "enhancement".

Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#readme-top">back to top</a>)</p>