## MAILTRAP
```bash
utilisation de mailtrap avec mon mail pro, modiffication .env MAIL DNS  a modifier
```
## MAIL
 ```shell 
 MailerInterface $mailer
 ```
## RESET PASWWORD 
```shell
 composer require symfonycasts/reset-password-bundle
    .....
 php bin/console make:reset-password

 email reset password pro.lucas.girard@gmail.com
 seulement les users 
```
## upload une image dans une BDD
```shell
#'code dans le typeform' 
 use Symfony\Component\Form\Extension\Core\Type\FileType;

#'code dans le builder'
    ->add('profilPicture', FileType::class, [
                'mapped' => false
            ])
    
# 'code dans le controller'
    use Symfony\Component\String\Slugger\SluggerInterface;

    /**
     * @Route("/new", name="candidate_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response '->!!ne pas oublier SluggerInterface $slugger'
    {
        $candidate = new Candidate();
        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


            /** @var UploadedFile $file */
            $file = $form->get('profilPicture')->getData();

            if ($file){
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
               
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                
                try {
                    $file->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $newFilename = 'error file upload';
                }

                $candidate->setProfilPicture($newFilename);
            }

            $entityManager->persist($candidate);
            $entityManager->flush();

            return $this->redirectToRoute('candidate_index');
        }

        return $this->render('candidate/new.html.twig', [
            'candidate' => $candidate,
            'form' => $form->createView(),
        ]);
    }

# 'mettre le dossier upload dans public ou autre, définir la chemin dans config/services.yaml
# expemple'
    parameters:
    pictures_directory: '%kernel.project_dir%/public/uploads/'


 ```
## liens vers la doc de symfony
    https://symfony.com/doc/current/controller/upload_file.html#creating-an-uploader-service
## save
```shell
    /**
     * @Route("/new", name="candidate_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $candidate = new Candidate();
        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


            /** @var UploadedFile $file */
            $file = $form->get('profilPicture')->getData();
            $file = $form->get('cv')->getData();
           

            if ($file){
               $filename = $this->saveUploadedFile($savefile);
                $candidate->setProfilPicture($newFilename);
            }

            $entityManager->persist($candidate);
            $entityManager->flush();

            return $this->redirectToRoute('candidate_index');
        }

        return $this->render('candidate/new.html.twig', [
            'candidate' => $candidate,
            'form' => $form->createView(),
        ]);
    }



    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
               
    $safeFilename = $slugger->slug($originalFilename);
    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

    
    try {
        $file->move(
            $this->getParameter('pictures_directory'),
            $newFilename
        );
    } catch (FileException $e) {
        $newFilename = 'error file upload';
    }
```
## Api PLatorm
```shell
## Commande pour installer Api PLatform 
symfony composer req api ou  composer req api 

## The Serialization Process Context, Groups and Relations.

### Configuration

# api/config/packages/framework.yaml
framework:
    serializer: { enable_annotations: true }

### Using Serialization Groups

# api/config/api_platform/resources.yaml
resources:
    App\Entity\Book:
        attributes:
            normalization_context:
                groups: ['read']
            denormalization_context:
                groups: ['write']

# api/config/serialization/Book.yaml
App\Entity\Book:
    attributes:
        name:
            groups: ['read', 'write']
        author:
            groups: ['write']
            

### @apiRessource()
Dans les entity faire une annotation avec @apiRessource() et use ApiPlatform\Core\Annotation\ApiResource; pour afficher les GET,POST ect ... en format JSON
sur la route /api.




```