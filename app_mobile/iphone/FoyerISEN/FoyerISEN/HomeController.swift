//
//  HomeController.swift
//  FoyerISEN
//
//  Created by Renald Morice on 13/03/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class HomeController: UIViewController {

    /*----------  VARIABLES  ----------*/    
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var banniereImageView: UIImageView!
    @IBOutlet weak var imageLoader: UIActivityIndicatorView!
    @IBOutlet weak var welcomeLabel: UILabel!
    /*--------------------------------*/
    
    // Au chargement de la vue
    //------------------------
    override func viewDidLoad() {
        super.viewDidLoad()
        
        //Pour le bouton menu
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:)) //"revealToggle:"
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        notificationsCenter.addObserver(self, selector: #selector(banniereDownloaded), name: MyNotifications.banniereDownloaded, object: nil)
        
        if let image: UIImage =  banniereManager.image {
            self.banniereImageView.image = image
            imageLoader.stopAnimating()
            imageLoader.hidden = true
        } else{
            imageLoader.startAnimating()
        }
        
        self.welcomeLabel.text = "Bienvenue \(user!)"
    }

    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    // Quand la bannière en téléchargée
    //---------------------------------
    func banniereDownloaded(){
        self.banniereImageView.image = banniereManager.image
        imageLoader.stopAnimating()
        imageLoader.hidden = true
    }
    
    deinit{
        notificationsCenter.removeObserver(self)
    }
    
}
