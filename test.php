#[Route('/', name: 'home')]
    public function listing(ManagerRegistry $doctrine): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $user = $this->getUser();

            $books = $doctrine->getRepository(Books::class)->findAll();
       
        return $this->render('home_page/index.html.twig', [
            'books' => $books,
        ]);
    }