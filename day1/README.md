# DAY 1 lABS


### Install Docker

Copy and paste the follwing on Ubuntu VM
``` sh
sudo apt install curl
curl -sSL https://get.docker.com/ | sh
sudo usermod -aG docker $(whoami)
```



#### Lab 1 - Docker
To perform these labs, kindly connect and login to your linux vm on Azure, ssh with putty already in your desktop. Run the command – <sudo -i> To switch to root

1.  What is the version of Docker Server Engine running on the VM ?
    run the command - ``` sh docker version ```
    ![alt text](img/docker-version.png "docker version")