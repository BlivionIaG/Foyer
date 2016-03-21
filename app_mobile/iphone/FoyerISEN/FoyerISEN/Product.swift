//
//  Product.swift
//  FoyerISEN
//
//  Created by Renald Morice on 27/02/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class Product: NSObject{
    
    var id_product : Int!
    var name : String!
    var firstLetter : String!
    var price : Int!
    var desc : String!
    var available : Int!
    var date : NSDate!
    var strUrlImage : String!
    
    init(id_product: Int, name: String, firstLetter : String, price: Int, desc: String, available: Int, date: NSDate, strUrlImage: String){
        super.init()
        
        self.id_product = id_product
        self.name = name
        self.firstLetter = firstLetter
        self.price = price
        self.desc = desc
        self.available = available
        self.date = date
        self.strUrlImage = strUrlImage
        
        
        //L'image ne sera pas gérer dans cette objet (CellView Product )
    }
    
    func content() -> String {
        return "id_product : \(id_product)\nname : \(name)\nfirstLetter : \(firstLetter)\nprice : \(price)\ndesc : \(desc)\navailable : \(available)\ndate : \(date)\nstrUrlImage : \(strUrlImage)\n"
    }

}
