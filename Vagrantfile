require 'yaml'
require 'fileutils'

required_plugins = %w( vagrant-hostmanager vagrant-vbguest vagrant-disksize )
required_plugins.each do |plugin|
    exec "vagrant plugin install #{plugin}" unless Vagrant.has_plugin? plugin
end

config = {
  local: './conf/vagrant-config.yml'
}

# read config
options = YAML.load_file config[:local]

# Domains for dns local
domains = {
    simple: options['simple_domain'],
    frontend_advanced: options['simple_domain'],
    backend_advanced: 'backend.' + options['simple_domain'],
    mail: 'mail.' + options['simple_domain'],
}

# check github token
#if options['github_token'].nil? || options['github_token'].to_s.length != 40
#  puts "You must place REAL GitHub token into configuration:\n/yii2-app-advanced/vagrant/config/vagrant-local.yml"
#  exit
#end

Vagrant.configure(2) do |config|
    # select the box
    config.vm.box = options['box']

    # should we ask about box updates?
    config.vm.box_check_update = options['box_check_update']

    # Configure disk size
    config.disksize.size = options['disksize']

    config.vm.provider 'virtualbox' do |vb|
        # machine cpus count
        vb.cpus = options['cpus']
        # machine memory size
        vb.memory = options['memory']
        # machine name (for VirtualBox UI)
        vb.name = options['machine_name']
    end

    # machine name (for vagrant console)
    config.vm.define options['machine_name']

    # machine name (for guest machine console)
    config.vm.hostname = options['machine_name']

    # network settings
    config.vm.network "private_network", ip: options['ip']
#    config.vm.network "public_network", ip: "192.168.0.26", bridge: "br0"
    config.vm.network "public_network"
    #config.vm.network "forwarded_port", guest: 3306, host: 3306

    # Configure synced_folder
    config.vm.synced_folder ".", "/vagrant",
        owner: "vagrant",
        group: "www-data",
        mount_options: ["dmode=775,fmode=775"]

    # ansible
    config.ssh.insert_key = false
    config.vm.provision "ansible_local" do |ansible|
      ansible.compatibility_mode = "2.0"
      ansible.playbook = "playbooks/vagrant.yml"
      ansible.extra_vars = {
          simple_domain: options['simple_domain'],
          php_default_version: options['php_default_version'],
          mysql_default_version: options['mysql_default_version'],
          mysql_default_pass: options['mysql_default_pass'],
          project_db_name: options['project_db_name'],
          project_user_name: options['project_user_name'],
          project_password: options['project_password'],
          ansible_python_interpreter: "/usr/bin/python3",
      }
    end

    config.vm.provision "shell",
            run: "always",
            inline: "eval `route -n | tail -n+3 | awk '{ if ($1 == \"0.0.0.0\" && ($8 !=\"eth0\" && $8 !=\"enp0s3\") && $2 != \"0.0.0.0\") print \"route del default gw \" $2; } '`"

    # hosts settings (host machine)
    config.vm.provision :hostmanager
    config.hostmanager.enabled            = true
    config.hostmanager.manage_host        = true
    config.hostmanager.ignore_private_ip  = false
    config.hostmanager.include_offline    = true
    config.hostmanager.aliases            = domains.values

    # post-install message (vagrant console)
    config.vm.post_up_message = "App:\n - App (Simple|Advanced frontend): http://#{domains[:simple]}\n - Mailcatcher: http://#{domains[:mail]}\n - Supervisor web admin: http://#{domains[:simple]}:9771\n - Rabbitmq web admin: http://#{domains[:simple]}:15672\n"
end
