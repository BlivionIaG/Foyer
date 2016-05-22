//
//  ConnectionController.swift
//  FoyerISEN
//
//  Created by Renald Morice on 13/03/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit


class ConnectionController: UIViewController, UITextFieldDelegate,  NetworkManagerDelegate {
    
    //ALERT
    var alertController = UIAlertController(title: "", message: "", preferredStyle: UIAlertControllerStyle.Alert)
    
    //Network
    var networkManager = NetworkManager.sharedInstance
    
    @IBOutlet weak var userTextField: UITextField!
    @IBOutlet weak var passwdTextField: UITextField!
    //@IBOutlet weak var label: UILabel!
    @IBOutlet weak var indicator: UIActivityIndicatorView!
    @IBOutlet weak var connectionButton: UIButton!
    @IBOutlet weak var fieldView: UIView!
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // On s'assure que les constantes network d'utilisateur sont bien clear
        networkManager.username = nil
        networkManager.authBasicKey = nil
        
        //Ajout de l'action (bouton OK) à l'alert
        alertController.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.Cancel,handler: nil))
        
        
        //Looks for single or multiple taps (Textfields).
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(ConnectionController.dismissKeyboard))
        view.addGestureRecognizer(tap)
        
        userTextField.delegate = self
        passwdTextField.delegate = self
        indicator.hidden = true
        
        fieldView.layer.cornerRadius = 5
        connectionButton.layer.cornerRadius = 2
        
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    // Bouton de connexion appuyé :
    //-----------------------------
    @IBAction func connectionButtonTouched(sender: AnyObject) {
        
        connectionButton.enabled = false
        indicator.hidden = false
        indicator.startAnimating()
        
        let postParams : [String : String] = [
            "username": userTextField.text!
            , "password": passwdTextField.text!
        ]
        networkManager.request(delegate : self, urlString: "cas/", requestType: "POST", postParams: postParams)
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
    func didReceiveData(response : String, tabData: NSArray) {
        
        print("reponse : \(response)")
        print(" data : \(tabData.description)")
        
        for item in tabData {
            
            // Interpretation retour connexion au CAS
            if let data = item as? NSDictionary {
                
                if let tabStatus : NSDictionary = data["status"] as? NSDictionary {
                    
                    if let errorString : String = tabStatus["error"] as? String {
                        
                        alertController.title = errorString
                        alertController.message = ""
                        //Affichage de l'alerte
                        presentViewController(alertController, animated: true, completion: nil)
                        
                    } else {
                        
                        if ( tabStatus["key"] == nil || tabStatus["username"] == nil){
                            
                            alertController.title = ""
                            alertController.message = "Pas d'erreur d'ID mais soit l'object JSON Key ou Username est nul !"
                            //Affichage de l'alerte
                            presentViewController(alertController, animated: true, completion: nil)

                            
                        } else{
                            networkManager.authBasicKey = tabStatus["key"] as? String
                            networkManager.username = tabStatus["username"] as? String
                            
                            //Switch à la page d'accueil
                            self.performSegueWithIdentifier("ConnectionSuccessful", sender: nil)
                        }
                    }
                    
                }
            }
            
            
        }
        //Une fois que la réponse est finie d'être traitée
        indicator.stopAnimating()
        indicator.hidden = true
        connectionButton.enabled = true
        
    }
    
    // Probème de connexion :
    //-----------------------
    func didFailToReceiveResponse(strError : String) {
        
        alertController.title = "Problème réseau : "
        alertController.message = strError
        //Affichage de l'alerte
        presentViewController(alertController, animated: true, completion: nil)
        
        indicator.stopAnimating()
        indicator.hidden = true
        connectionButton.enabled = true
    }
    
    //-------------------------
    //MARK: UITextFieldDelegate
    //-------------------------
    
    func textFieldShouldReturn(textField: UITextField) -> Bool {
        textField.resignFirstResponder()
        return true;
    }
    
    
}


