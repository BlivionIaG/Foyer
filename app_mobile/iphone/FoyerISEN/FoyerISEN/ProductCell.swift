//
//  ProductCell.swift
//  FoyerISEN
//
//  Created by Renald Morice on 21/05/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class ProductCell: UITableViewCell {
    
    /*----------  VARIABLES  ----------*/
    var product: Product!
    
    @IBOutlet weak var productImage: UIImageView!
    @IBOutlet weak var imageLoader: UIActivityIndicatorView!
    @IBOutlet weak var productNameLabel: UILabel!
    @IBOutlet weak var productDesc: UITextView!
    @IBOutlet weak var productPrice: UILabel!
    /*--------------------------------*/

    //Chargement de la cellule
    //------------------------
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
        
        notificationsCenter.addObserver(self, selector: #selector(productImageDownloaded), name: MyNotifications.productImageDownloaded, object: nil)
        
        imageLoader.startAnimating()
        productDesc.userInteractionEnabled = false
    }
    

    //Quand une cellule est selectionnée
    override func setSelected(selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
        
    }
    
    
    //Gérer l'affichage d'une cellule
    //-------------------------------
    func setProductCell(product: Product){
        
        self.product = product
        
        if let image: UIImage =  self.product.image {
            self.productImage.image = image
            imageLoader.stopAnimating()
            imageLoader.hidden = true
        } else {
            imageLoader.startAnimating()
        }
    

        self.productNameLabel.text = product.name
        
        if product.available == 1 { // 1 : Dispo
            self.productNameLabel.textColor = UIColor(netHex: MyHexaColors.green)
        }else{
            self.productNameLabel.textColor = UIColor(netHex: MyHexaColors.red)
        }
        
        self.productDesc.text = product.desc
        self.productPrice.text = String("\(product.price) €")
    }
    
    
    //Lorsque l'objet product a téléchargé son image
    //----------------------------------------------
    func productImageDownloaded(){
        self.productImage.image = self.product.image
        imageLoader.stopAnimating()
        imageLoader.hidden = true
    }
    
    deinit{
        notificationsCenter.removeObserver(self)
    }

}
