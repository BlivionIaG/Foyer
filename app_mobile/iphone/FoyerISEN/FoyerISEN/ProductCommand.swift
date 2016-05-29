//
//  ProductCommand.swift
//  FoyerISEN
//
//  Created by Renald Morice on 29/05/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class ProductCommand: NSObject {
    
    var id_product: Int!
    var quantity: Int!
    
    init(id_product: Int, quantity: Int){
        super.init()
        
        self.id_product = id_product
        self.quantity = quantity
        
    }

}
