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
