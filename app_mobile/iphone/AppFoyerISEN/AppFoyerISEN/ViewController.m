//
//  ViewController.m
//  AppFoyerISEN
//
//  Created by Renald Morice on 15/02/2016.
//  Copyright © 2016 Renald Morice. All rights reserved.
//

#import "ViewController.h"
#import "AFNetworking.h"

@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    //Suppression de tous les cookies
    //-------------------------------
    NSHTTPCookieStorage *cookieStorage = [NSHTTPCookieStorage sharedHTTPCookieStorage];
    for (NSHTTPCookie *each in cookieStorage.cookies) {
        [cookieStorage deleteCookie:each];
    }
    
    
    NSURLSession *session = [NSURLSession sharedSession];
    
    NSURLSessionTask *task = [session dataTaskWithURL: [NSURL URLWithString:@"https://web.isen-bretagne.fr/cas/login"] completionHandler:^(NSData *data, NSURLResponse *response, NSError *error) {
        
        // handle response
        
        if(error == nil){
            
            // Réponse HTTP et contenu
            //------------------------
            //NSLog(@"response = %@", response);
            NSString * text = [[NSString alloc] initWithData: data encoding: NSUTF8StringEncoding];
            //NSLog(@"Data = %@",text);
            
            //Récupération de l'élément html "input" avec l'attribut name à 'lt'
            //------------------------------------------------------------------
            NSRange   searchedRange = NSMakeRange(0, [text length]);
            NSString *pattern = @"name=\"lt\" value=\"([a-zA-Z0-9-_]+)\"";
            NSError  *regexError = nil;
            
            NSRegularExpression* regex = [NSRegularExpression regularExpressionWithPattern: pattern options:0 error:&regexError];
            NSArray* matches = [regex matchesInString:text options:0 range: searchedRange];
            for (NSTextCheckingResult* match in matches) {
                //NSString* matchText = [text substringWithRange:[match range]];
                //NSLog(@"match: %@", matchText);
                NSRange group1 = [match rangeAtIndex:1];
                self.lt =[ text substringWithRange:group1];
                NSLog(@"champ 'lt' : %@", self.lt);
                
            }
            
        }
        else NSLog(@"error : %@", error);
        
        //Récupération du cookie JSESSIONID
        //---------------------------------
        NSHTTPURLResponse *httpResp = (NSHTTPURLResponse*) response;
        NSArray *cookies = [NSHTTPCookie cookiesWithResponseHeaderFields:[httpResp allHeaderFields] forURL:[response URL]];
        
        for (NSHTTPCookie *cookie in cookies) {
            
            if([cookie.name  isEqual: @"JSESSIONID"]){
                self.jSessionId = cookie.value;
                NSLog( @"JSESSIONID :  %@",  self.jSessionId);
            }
        }
        
        
    }];
    
    [task resume];
    
    
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


@end
