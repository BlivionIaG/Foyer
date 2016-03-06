//
//  ViewController.swift
//  FoyerISEN
//
//  Created by Renald Morice on 25/02/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit


class ViewController: UIViewController, UITextFieldDelegate,  NetworkManagerDelegate {
    
    var networkManager = NetworkManager.sharedInstance

    @IBOutlet weak var userTextField: UITextField!
    @IBOutlet weak var passwdTextField: UITextField!
    @IBOutlet weak var label: UILabel!
    @IBOutlet weak var indicator: UIActivityIndicatorView!
    @IBOutlet weak var connectionButton: UIButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        //Looks for single or multiple taps.
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: "dismissKeyboard")
        view.addGestureRecognizer(tap)
        
        userTextField.delegate = self
        passwdTextField.delegate = self
        indicator.hidden = true
        
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    // Bouton de connexion appuyé :
    //-----------------------------
    @IBAction func connexionTouched(sender: AnyObject) {
        
        connectionButton.hidden = true
        indicator.hidden = false
        indicator.startAnimating()
        
        let postParams : [String : String] = [
            "username": userTextField.text!
            , "password": passwdTextField.text!
        ]
        networkManager.request(delegate : self, urlString: "http://foyer.p4ul.tk/api/cas/", requestType: "POST", postParams: postParams)
    }
    
    
    //Calls this function when the tap is recognized.
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    //----------------------------
    //MARK: NetworkManagerDelegate
    //----------------------------
    
    
    // Réponse serveur :
    //------------------
    func didReceiveResponse(response : String, tabData: NSArray) {
        
        print("reponse : \(response)")
        print(" data : \(tabData.description)")
        
        for item in tabData {

            // Interpretation retour connexion au CAS
            if let data = item as? NSDictionary {
                
                if let tabStatus : NSDictionary = data["status"] as? NSDictionary {
                    
                    if let errorString : String = tabStatus["error"] as? String {
                        label.text = errorString
                        
                    } else {
                        
                        if ( tabStatus["key"] == nil || tabStatus["username"] == nil){
                            label.text = "Problème de connexion : contacter le support !"
                            print("Pas d'erreur d'ID mais soit l'object JSON Key ou Username est nul !")
                        } else{
                            networkManager.authBasicKey = tabStatus["key"] as? String
                            networkManager.username = tabStatus["username"] as? String
                            label.text = "Connexion établie"
                        }
                    }
                    
                }
            }
            
        }
        //Une fois que la réponse est finie d'être traitée
        indicator.stopAnimating()
        indicator.hidden = true
        connectionButton.hidden = false
        
    }
    
    // Probème de connexion :
    //-----------------------
    func didFailToReceiveResponse(strError : String) {
        label.text = "Problème de connexion : contacter le support !"
        print( "UIViewController : \(strError)")
    }
    
    //-------------------------
    //MARK: UITextFieldDelegate
    //-------------------------
    
    func textFieldShouldReturn(textField: UITextField) -> Bool {
        textField.resignFirstResponder()
        return true;
    }
    
    
}

