<h1>IS 226: Proposal to be Approved</h1>
<h3>Contributors</h3>
<ul>
    <li>Jesus Gerard De ramos</li>
    <li>John Christian Marques</li>
</ul>

<h1>Development Setup (Windows)</h1>
<h3>Put Up the Web Server</h3>
<ol>
    <li>Download the <a href="https://windows.php.net/downloads/releases/php-8.1.3-nts-Win32-vs16-x64.zip">PHP Zip</a> and unpack it in your preferred location.
        <h5><strong>Note:</strong> I placed mine under C:/Program File(x86)</h5>
    </li>
    <li>Download and install <a href="https://getcomposer.org/Composer-Setup.exe">Composer</a> ensuring that it is going to use the PHP previously downloaded.</li>
    <li>Clone this repository to your preferred directory</li>
    <li>Open a command line in the location where the repository is cloned</li>
    <li>On the command line, run <code>composer install</code>. This will install the dependencies needed for the project.</li>
    <li>Finally, run <code>symfony server:start</code> to start up the server. The <a href="http://localhost:8000">Project</a> should now be accessible via browser.</a> 
</ol>
<h3>Setup database connection</h3>
<ol>
    <li>Install MySQL version 8.0.28, be sure to remember your credentials.</li>
    <li>Update the .env file, uncomment the DATABASE_URL for mysql and supply it with your own credentials. You don't have to have the schema created in the database, only the user.</li>
    <li>Open a command line on the source code directory</li>
    <li>Run "php bin/console doctrine:database:create" to create the schema</li>
    <li>Run "php bin/console doctrine:migrations:migrate" to create the databse objects based on the migration script</li>
</ol>
<h3>Setup Vue JS</h3>
<ol>
    <li>Open another command line on the source code directory</li>
    <li>Run "npm install"</li>
    <li>Run "npm run watch"</li>
    <li>Access "http://localhost:8000/user/bootstrap" to see the page with Vue and Bootstrap</li>
</ol>