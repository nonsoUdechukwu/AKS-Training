# DAY 1 LABS


### Install Docker

Copy and paste the follwing on Ubuntu VM
``` 
sudo apt install curl
curl -sSL https://get.docker.com/ | sh
sudo usermod -aG docker $(whoami)
```



### Lab 1 - Docker
To perform these labs, kindly connect and login to your linux vm on Azure (https://portal.azure.com), ssh with putty already in your desktop. Run the command – ```  sudo -i ``` To switch to root

1.  What is the version of Docker Server Engine running on the VM?
    
    run the command - ```  docker version ```

    ![alt text](../img/docker-version.png "docker version")

2.  Check if any containers are running on the host
     
    run the command - ```  docker ps ```

    ![alt text](../img/docker-ps.png "docker ps")

3.  Check the number images running on the host 
   
    run the command - ```  docker images ```
    ![alt text](../img/docker-img.png "docker image")

4.  Run a container with the nginx:latest image and name it webapp
    
    run the command - ```  docker run --name webapp nginx:latest ``` 

    ![alt text](../img/docker-output.png "docker image")

     #press ctrl + c to terminate the container

5.  Check the number images running on the host 

    run the command - ```  docker images ```

     ![alt text](../img/docker-img2.png "docker image")

6.  Check if any containers are running on the host

    run the command -  ```  docker ps ```
    
    ![alt text](../img/docker-ps.png "docker ps")

7.  Check all exited containers

    run the command -```  docker ps -a ```

    ![alt text](../img/docker-psa.png "docker ps -a")

8.  Start the webapp container again
 
    run the command - ```  docker start webapp ```

     ![alt text](../img/docker-start.png "docker start")

9.  Get the containerid of the webapp container and confirm the port number

     run the command - ```  docker ps -a ```

     ![alt text](../img/docker-psa2.png "docker ps -a")


10. Get the ip address of the webapp

     run the command - ```  docker inspect <containerid> | grep “IPAddress” ```

    ![alt text](../img/docker-inspect.png "docker get ip address")

 
11. Use curl to confirm the webapp container is running.# This would display a sample page

    run the command -```  curl 172.17.0.2:80 ```

   ![alt text](../img/curl-output.png "curl output")

12. Run another container Apache and terminate with ctl + c

    run the command - ```  docker run --name apache httpd ```

     ![alt text](../img/docker-run2.png "docker run")

13. Check the number images running on the host 

    run the command - ```  docker images  ```

    ![alt text](../img/docker-img3.png "docker run")


14. Start the webapp container again 

    run the command - ```  docker start apache ```

    ![alt text](../img/docker-start.png "docker start")

15. Check system-wide information for Docker.

    run the command - ```  docker info ```

     ![alt text](../img/docker-info.png "docker info")

16. log into a Docker registry
    run the command - ```  docker login ```

    ![alt text](../img/docker-login.png "docker login")

17. Build a docker image 
    **Open vim editor** _``` vi index.py ```
     ```python
    from flask import Flask
    app = Flask(__name__)

    @app.route("/")
    def hello():
        return "Welcome to AKS Training “

    if __name__ == "__main__":
        app.run(host="0.0.0.0", port=int("5000"), debug=True)
    ```
    **press escape i to edit the file, right click and paste the above python file then press wq! To save and close**

    Create a docker file

    ``` vi Dockerfile ```
    **press escape i to edit the file, right click and paste the below dockerfile  then press wq! To save and close**

    ```YAML
    FROM python:alpine3.7
    COPY . /app
    WORKDIR /app
    RUN pip install -r requirements.txt
    EXPOSE 5000
    CMD python ./index.py
    ```
    Finally create the requirements file requirements.txt

    ``` vi requirements.txt ```
    enter "**flask**" and save(**wq!**)

    Now build the container -
    ``` docker build -t pythonapp . ```  

    Run the container
    ``` docker run -d -p 5000:5000 pythonapp ```

    ![alt text](../img/docker-run3.png "docker run")

    Now to confirm let curl the url - ```curl http://localhost:5000 ```

    ![alt text](../img/curl2.png "curl")

18.	Delete the apache image
    ``` docker stop container-id ```
    ``` docker rmi -f image-id ```
    ![alt text](../img/docker-delete.png "docker delete")




### Lab 2 – Kubernetes

 We will setup a kubernetes manually on Azure 


1. **Lab 2.1** - Create a vm on Azure - visit https://shell.azure.com

   **Create a resource group**
   ``` az group create -n CustomKubeCluster -l eastus ``` --

  
 2. **Create a Create Virtual network for the kubernetes cluster**
   ``` az network vnet create -g CustomKubeCluster -n KubeVNet --address-prefix 172.0.0.0/16 --subnet-name MySubnet --subnet-prefix 172.0.0.0/24 ``` 


   

3. **Create Master Node - This will have a public IP**

   ``` az vm create -g "CustomKubeCluster" -n "kube-master" --image "UbuntuLTS" --size Standard_B2s --data-disk-sizes-gb 10  --admin-username "demouser" --admin-password "Demouser@123" --public-ip-address-dns-name kubeadm-master```  

4. **Create the two worker nodes - This will have a public IP** 

   ``` az vm create -g "CustomKubeCluster" -n "kube-worker-1" --image "UbuntuLTS" --size Standard_B2s --data-disk-sizes-gb 10  --admin-username "demouser" --admin-password "Demouser@123" --public-ip-address-dns-name kubeadm-worker-1```  

   

    ``` az vm create -g "CustomKubeCluster" -n "kube-worker-2" --image "UbuntuLTS" --size Standard_B2s --data-disk-sizes-gb 10  --admin-username "demouser" --admin-password "Demouser@123" --public-ip-address-dns-name kubeadm-worker-2```  

5. **Install Packages** 
  ```cat <<EOF | sudo tee /etc/modules-load.d/containerd.conf overlay  br_netfilter EOF```
  ```sudo modprobe overlay```
  ```sudo modprobe br_netfilter ```
  ```cat <<EOF | sudo tee /etc/sysctl.d/99-kubernetes-cri.conf net.bridge.bridge-nf-call-iptables = 1 net.ipv4.ip_forward = 1 net.bridge.bridge-nf-call-ip6tables = 1 EOF ```

  ```sudo sysctl --system ```
  
6. **install containerd**

    ```sudo apt-get update && sudo apt-get install -y containerd``` 
    #### Inside the /etc folder, create a configuration file for containerd and generate the default configuration file

    ```sudo mkdir -p /etc/containerd ```
    ```sudo containerd config default | sudo tee /etc/containerd/config.toml ```
    #### Now restart containerd to ensure new configuration file usage 
    ``` sudo systemctl restart containerd ```

    #### Disable swap
    ```sudo swapoff -a ```
    ``` sudo sed -i '/ swap / s/^\(.*\)$/#\1/g' /etc/fstab ```

    #### install dependency packages
    ```sudo apt-get update && sudo apt-get install -y apt-transport-https curl ```
    ```curl -s https://packages.cloud.google.com/apt/doc/apt-key.gpg | sudo apt-key add - ```
    #### Add kubernetes repository list
    ``` cat <<EOF | sudo tee /etc/apt/sources.list.d/kubernetes.list deb https://apt.kubernetes.io/ kubernetes-xenial main EOF ```

    ### update packages
    ```sudo apt-get update ```

7. **Install Kubeadm, kubelet and kubectl - kubeadm: The command to bootstrap the cluster. kubelet: The component that runs on all of the machines in your cluster and does things like starting pods and containers. kubectl: The command line util to talk to your cluster.**


    ``` sudo apt-get install -y kubelet=1.20.1-00 kubeadm=1.20.1-00 kubectl=1.20.1-00 ```

    #### Turn off automatic updates
    ``` sudo apt-mark hold kubelet kubeadm kubectl ```

9. **Initailze Kubeadm cluster**

    ``` sudo kubeadm init --pod-network-cidr 192.168.0.0/16 ```
10. **Set kubectl access**

    ```mkdir $HOME/.kube```
    ```sudo cp -I /etc/kubernetes/admin.conf $HOME/.kube/config```
    ```sudo chown $(id -u):$(id -g) $HOME/.kube/config ```


11. **Test access to cluster**

    ```kubectl version ```

12. **Install Calico Network Add-On(Calico provides a simple, high-performance, secure networking. Calico is trusted by the major cloud providers, with EKS, AKS, GKE, and IKS all having integrated Calico as part of their offerings.)**


    ```kubectl apply -f https://docs.projectcalico.org/manifests/calico.yaml ```

13. **Check if pods are running**

    ``` kubectl get pods -n kube-system ```

14. **Join the worker nodes to the Cluster**
  ```kubeadm token create --print-join-command ```

  #### In worker nodes, paste the kubeadm join command to join the cluster
  ``` sudo kubeadm join <join command from previous command>```

#### confirm the nodes on the master
``` kubectl get nodes ```
