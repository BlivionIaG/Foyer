//
//  Product.swift
//  FoyerISEN
//
//  Created by Renald Morice on 27/02/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class Product: NSObject {
    var id_product : Int
    var name : String
    var price : Int
    var desc : String
    var available : Int
    var date : NSDate
    var image : UIImage
    
    init(id_product: Int, name: String, price: Int, desc: String, available: Int, date: NSDate, image: UIImage){
        
        self.id_product = id_product
        self.name = name
        self.price = price
        self.desc = desc
        self.available = available
        self.date = date
        self.image = image
    }

}
