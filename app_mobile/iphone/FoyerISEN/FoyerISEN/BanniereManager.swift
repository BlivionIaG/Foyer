//
//  BanniereManager.swift
//  FoyerISEN
//
//  Created by Renald Morice on 29/05/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class BanniereManager: NSObject, NetworkManagerDelegate {
    
    /*----------  VARIABLES  ----------*/
    var image: UIImage?
    
    //class variable : SINGLETON
    class var sharedInstance: BanniereManager {
        struct Singleton {
            static let instance = BanniereManager()
        }
        return Singleton.instance
    }
    /*---------------------------------*/
    
    //Au chargement de l'objet
    //------------------------
    override init(){
        super.init()
    }
    
    //Charger la bannière
    //-------------------
    func loadBanniere(){
        self.image = nil
        networkManager.request(delegate: self, urlString: "banniere/", requestType: "GET")
    }
    
    //Reception de l'url à laquelle on peut trouver la bannière
    //---------------------------------------------------------
    func didReceiveData(response: String, tabData: NSArray) {
        
        for item in tabData {
            
            if let strUrlBanniere : String = item["url"] as? String {
                networkManager.downloadImage(delegate: self, urlString: "files/mobile/"+strUrlBanniere)
            } else {
                print("erreur urlBanniere")
            }
            
        }
        
    }
    
    //Quand l'image est reçue
    //-----------------------
    func didReceiveImage(image: UIImage) {
        self.image = image
        notificationsCenter.postNotificationName(MyNotifications.banniereDownloaded, object: self)
    }
    
    //Problème réseau
    //---------------
    func didFailToReceiveResponse(strError: String) {
        print(strError)
    }

}
