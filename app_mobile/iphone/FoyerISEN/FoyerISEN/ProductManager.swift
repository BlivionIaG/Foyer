//
//  ProductManager.swift
//  FoyerISEN
//
//  Created by Renald Morice on 29/05/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class ProductManager: NSObject, NetworkManagerDelegate {
    
    /*----------  VARIABLES  ----------*/
    var products = [Product]()
    
    //class variable : SINGLETON
    class var sharedInstance: ProductManager {
        struct Singleton {
            static let instance = ProductManager()
        }
        return Singleton.instance
    }
    /*---------------------------------*/
    
    //Au chargement de l'objet
    //------------------------
    override init(){
        super.init()
    }
    
    //Charger les produits
    //--------------------
    func loadProducts(){
        self.products.removeAll()
        networkManager.request(delegate: self, urlString: "product/", requestType: "GET")
    }
    
    //Lors de la reception des produits
    //---------------------------------
    func didReceiveData(response: String, tabData: NSArray) {
        
        /*print("response : " + response)
         print("tabData" + tabData.description)*/
        
        for item in tabData {
            
            var id_product : Int!
            var name : String!
            var firstLetter : String!
            var price : Int!
            var desc : String!
            var available : Int!
            var date : NSDate!
            var strUrlImage : String!
            
            if let strAvailable : String = item["available"] as? String {
                available = Int(strAvailable)!
                
            } else {
                print("erreur available")
            }
            
            if let strDate : String = item["date"] as? String {
                let dateFormatter = NSDateFormatter()
                dateFormatter.dateFormat = "yyyy-MM-dd HH:mm:ss"
                date = dateFormatter.dateFromString(strDate)!
                
            } else {
                print("erreur date")
            }
            
            if let strDescription : String = item["description"] as? String {
                desc = strDescription
            } else {
                print("erreur description")
            }
            
            if let str_first_letter : String = item["first_letter"] as? String {
                firstLetter = str_first_letter
            } else {
                print("erreur first_letter")
            }
            
            if let str_id_product : String = item["id_product"] as? String {
                id_product = Int(str_id_product)
            } else {
                print("erreur id_product")
            }
            
            if let strUrlImg : String = item["image"] as? String {
                strUrlImage = strUrlImg
            } else {
                print("erreur image")
            }
            
            if let strName : String = item["name"] as? String {
                name = strName
            } else {
                print("erreur name")
            }
            
            if let strPrice : String = item["price"] as? String {
                price = Int(strPrice)
            } else {
                print("erreur price")
            }
            
            self.products += [Product(id_product: id_product
                ,name: name
                ,firstLetter: firstLetter
                ,price: price
                ,desc: desc
                ,available: available
                ,date: date
                ,strUrlImage: strUrlImage
                )]
            
        }
        
        /*for product in products {
         print(product.content())
         }*/
        notificationsCenter.postNotificationName(MyNotifications.productsDownloaded, object: self)
        
    }
    
    //Problème réseau
    //---------------
    func didFailToReceiveResponse(strError: String) {
        print(strError)
    }

}
