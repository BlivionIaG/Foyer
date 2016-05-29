//
//  CommandManager.swift
//  FoyerISEN
//
//  Created by Renald Morice on 29/05/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class CommandManager: NSObject, NetworkManagerDelegate {
    
    /*----------  VARIABLES  ----------*/
    var commands = [Command]()
    
    //class variable : SINGLETON
    class var sharedInstance: CommandManager {
        struct Singleton {
            static let instance = CommandManager()
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
    func loadCommands(){
        self.commands.removeAll()
        
        networkManager.request(delegate: self, urlString: "/command/login/\(user!)", requestType: "GET")
    }
    
    
    //Lors de la reception des commandes
    //----------------------------------
    func didReceiveData(response: String, tabData: NSArray) {
        
        /*print("response : " + response)
        print("tabData" + tabData.description)*/
        
        for item in tabData {
            
            var id_command : Int!
            var date : NSDate!
            var periode_debut : String!
            var periode_fin : String!
            var tabProductCommand = [ProductCommand]()
            var state: Int!
            var total : Int!
            
            if let str_id_command : String = item["id_command"] as? String {
                id_command = Int(str_id_command)
                
            } else {
                print("erreur id_command")
            }
            
            if let strDate : String = item["date"] as? String {
                let dateFormatter = NSDateFormatter()
                dateFormatter.dateFormat = "yyyy-MM-dd"
                date = dateFormatter.dateFromString(strDate)!
                
            } else {
                print("erreur date")
            }
            
            if let str_periode_debut : String = item["periode_debut"] as? String {
                periode_debut = str_periode_debut
            } else {
                print("erreur periode_debut")
            }
            
            if let str_periode_fin : String = item["periode_fin"] as? String {
                periode_fin = str_periode_fin
            } else {
                print("erreur periode_fin")
            }
            
            
            if let products:NSArray = item["product"] as? NSArray{
                
                for product in products{
                    
                    var id_product: Int!
                    var quantity : Int!
                    
                    if let str_id_product : String = product["id_product"] as? String {
                        id_product = Int(str_id_product)
                        
                    } else {
                        print("erreur id_product")
                    }
                    
                    if let str_quantity : String = product["quantity"] as? String {
                        quantity = Int(str_quantity)
                        
                    } else {
                        print("erreur quantity")
                    }
                    
                    tabProductCommand += [ProductCommand(id_product: id_product, quantity: quantity)]
                }
            }
            
            if let str_state : String = item["state"] as? String {
                state = Int(str_state)
                
            } else {
                print("erreur id_command")
            }
            
            if let int_total : Int = item["total"] as? Int{ //La méthode avec String ne fonctionne pas ?!!
                total = int_total
                
            } else {
                print("erreur total")
            }
            
            
            self.commands += [Command(id_command: id_command
                ,date: date
                ,periode_debut: periode_debut
                ,periode_fin: periode_fin
                ,tabProductCommand: tabProductCommand
                ,total: total
                ,state: state
                )]
            
        }
        
        notificationsCenter.postNotificationName(MyNotifications.commandDownloaded, object: self)
        
    }
    
    //Problème réseau
    //---------------
    func didFailToReceiveResponse(strError: String) {
        print(strError)
    }
    

}
