//
//  ProductDetailCrontroller.swift
//  FoyerISEN
//
//  Created by Renald Morice on 24/05/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class ProductDetailCrontroller: UIViewController {
    
    var product: Product?
    
    @IBOutlet weak var productImageView: UIImageView!
    @IBOutlet weak var productNameLabel: UILabel!
    @IBOutlet weak var productStateLabel: UILabel!
    @IBOutlet weak var productPriceLabel: UILabel!
    @IBOutlet weak var productDescTextView: UITextView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        notificationsCenter.addObserver(self, selector: #selector(notifyObservers), name: MyNotifications.productImageDownloaded, object: nil)
        
        self.productImageView.image = product?.image
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
        //self.productDescTextView.userInteractionEnabled = false
        
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    //Lorsque l'objet product envoi une notification pour signaler que son image a été téléchargée...
    func notifyObservers(){
        self.productImageView.image = self.product!.image
    }
    
    deinit{
        notificationsCenter.removeObserver(self)
    }

}
