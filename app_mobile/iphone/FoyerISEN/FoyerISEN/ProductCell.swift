//
//  ProductCell.swift
//  FoyerISEN
//
//  Created by Renald Morice on 21/05/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class ProductCell: UITableViewCell {
    
    
    @IBOutlet weak var productImage: UIImageView!
    @IBOutlet weak var productNameLabel: UILabel!
    @IBOutlet weak var productDesc: UITextView!
    @IBOutlet weak var productPrice: UILabel!
    
    var product: Product!

    
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
        
        notificationsCenter.addObserver(self, selector: #selector(notifyObservers), name: MyNotifications.productImageDownloaded, object: nil)
        
        productDesc.userInteractionEnabled = false
    }
    

    override func setSelected(selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
        // Configure the view for the selected state
    }
    
    
    
    func setProductCell(product: Product){
        
        self.product = product
        
        if let image: UIImage =  self.product.image {
            self.productImage.image = image
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
    
    
    //Lorsque l'objet product envoi une notification pour signaler que son image a été téléchargée...
    func notifyObservers(){
        self.productImage.image = self.product.image
    }
    
    deinit{
        notificationsCenter.removeObserver(self)
    }

}
