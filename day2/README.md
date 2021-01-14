# DAY 2 LABS


### Create a Kubernetes cluster through Azure CLI

1. Launch an Azure Cloud Shell session by clicking the Cloud Shell button on the top-right menu bar in the Azure Portal.


  ![alt text](../img/az-shell.png "Azure Shell")


2. Create a resource group to deploy your AKS cluster by entering the following Azure CLI
command.
   ``` az group create --name aks-training --location westeurope ```

  ![alt text](../img/az-rg.png "Azure Shell")


3. Use the az aks create command to create the AKS cluster. The following example creates a cluster named akstraining with one node, and the --enable-addons monitoring parameter will enable Azure Monitor for containers for this cluster.
    ``` 
    az aks create --resource-group aks-training --name aks-demo1 --node-count 3 --enableaddons
    monitoring --generate-ssh-keys
    ```

4. Connect to the Azure AKS cluster

     ``` az aks get-credentials --resource-group aks-training --name aks-demo1 ```


5. Use kubectl to get the number of nodes on the cluster
  

   ``` kubectl get nodes```


6. Clone the application from github

    ``` git clone https://github.com/nonsoUdechukwu/AKS-Training.git ```

7. Enter inside the application directory and view the yaml file
   
   ```
   cd day2
   cd sampleapp
   code azure-vote.yaml
   ```

    ![alt text](../img/app-deploy-code.png "Azure kube App deploy")

8. Create the application
   
   ``` kubectl create -f azure-vote.yaml ```

9. Check the progress of the application creation –
   
   ```
   kubectl get pods --watch
   kubectl get pods
   ```

10. To get the public address of the load balancer type the command
   
   ```
   kubectl get service azure-vote-front --watch
   ```
   ![alt text](../img/app-deploy-code2.png "Azure kuibe App deploy")

11. Note the external IP and type it in a browser


12. Clean up environment. To delete every resource created in Kubernetes for this application, you simply need to delete by the manifest files since everything was created from the file.
   


