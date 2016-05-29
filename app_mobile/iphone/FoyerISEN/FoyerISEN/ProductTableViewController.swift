//
//  ProductTableViewController.swift
//  FoyerISEN
//
//  Created by Renald Morice on 28/03/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class ProductTableViewController: UITableViewController {
    
    /*----------  VARIABLES  ----------*/
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet var productTableView: UITableView!
    /*--------------------------------*/
    
    //Au chargement de la vue
    //-----------------------
    override func viewDidLoad() {
        super.viewDidLoad()
        
        //Pour le bouton menu
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        notificationsCenter.addObserver(self, selector: #selector(productsDownloaded), name: MyNotifications.productsDownloaded, object: nil)
    }
    
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
    }
    
    //Nombre de cellules
    //------------------
    override func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return productsManager.products.count
    }

    
    // Configuration de chaque cellule
    //--------------------------------
    override func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {

        let cell = tableView.dequeueReusableCellWithIdentifier("ProductCell") as! ProductCell
        //let product = products[indexPath.row]
        //cell.setProductCell( UIImage(named: "smallIcon")!, productName: product.name, productDesc: product.desc)
        cell.setProductCell( productsManager.products[indexPath.row] )
        
        return cell
    }

    //Changement d'écran vers l'écran de détail du produit
    //----------------------------------------------------
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        
        if let identifier = segue.identifier {
            
            switch identifier {
                
                case "ProductDetailSegue":
                    let productDetailVC = segue.destinationViewController as! ProductDetailCrontroller
                    if let indexPath = self.tableView.indexPathForCell(sender as! ProductCell) {
                        productDetailVC.product = productsManager.products[indexPath.row]
                    }
            
                default : break
            }
        }
        
    }
    
    //Quand les produits sont téléchargés
    //-----------------------------------
    func productsDownloaded(){
        self.tableView.reloadData()
    }
    
    
    deinit{
        notificationsCenter.removeObserver(self)
    }

    
}
