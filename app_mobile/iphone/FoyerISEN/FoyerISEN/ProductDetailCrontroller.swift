//
//  ProductDetailCrontroller.swift
//  FoyerISEN
//
//  Created by Renald Morice on 24/05/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class ProductDetailCrontroller: UIViewController {
    
    /*----------  VARIABLES  ----------*/
    var product: Product?
    
    @IBOutlet weak var productImageView: UIImageView!
    @IBOutlet weak var imageLoader: UIActivityIndicatorView!
    @IBOutlet weak var productNameLabel: UILabel!
    @IBOutlet weak var productStateLabel: UILabel!
    @IBOutlet weak var productPriceLabel: UILabel!
    @IBOutlet weak var productDescTextView: UITextView!
    /*-------------------------------*/
    
    //Au chargement de la vue
    //-----------------------
    override func viewDidLoad() {
        super.viewDidLoad()
        
        notificationsCenter.addObserver(self, selector: #selector(productImageDownloaded), name: MyNotifications.productImageDownloaded, object: nil)
        
        if let image: UIImage = product?.image{
            self.productImageView.image = image
            imageLoader.stopAnimating()
            imageLoader.hidden = true
        } else {
            imageLoader.startAnimating()
        }
        
        self.productNameLabel.text = product?.name
        
        self.productPriceLabel.text = String("\(product!.price) €")
        
        if (product?.available == 1) {
            self.productStateLabel.text = "Disponible"
            self.productStateLabel.textColor = UIColor(netHex: MyHexaColors.green)
        }else{
            self.productStateLabel.text = "Non disponible"
            self.productStateLabel.textColor = UIColor(netHex: MyHexaColors.red)
        }
        
        self.productDescTextView.text = product!.desc
        self.productDescTextView.userInteractionEnabled = false
        
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    //Lorsque l'objet product a téléchargé son image
    //----------------------------------------------
    func productImageDownloaded(){
        self.productImageView.image = self.product!.image
        imageLoader.stopAnimating()
        imageLoader.hidden = true
    }
    
    deinit{
        notificationsCenter.removeObserver(self)
    }

}
