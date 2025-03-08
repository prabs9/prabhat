const express = require("express");
const cors = require("cors");
const path = require("path");
const app = express();
const multer = require("multer");
const { PrismaClient } = require("@prisma/client");
const prisma = new PrismaClient();

// Enable CORS for all origins
app.use(cors());

app.use(express.json());
app.use(express.static(path.join(__dirname)));

const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    cb(null, "IMAGES/");
  },
  filename: function (req, file, cb) {
    const uniqueSuffix = Date.now() + "-" + Math.round(Math.random() * 1e9);
    const fileExtension = file.originalname.split(".").pop();
    cb(null, `product-${uniqueSuffix}.${fileExtension}`);
  },
});

const upload = multer({ storage: storage });

// Serve the HTML file
app.get("/", (req, res) => {
  res.sendFile(path.join(__dirname, "index.html"));
});

app.post("/api/addproducts", upload.single("image"), async (req, res) => {
  try {
    const { name, price, description } = req.body;
    const imageName = req.file ? req.file.filename : "";

    const product = await prisma.products.create({
      data: {
        name,
        price: parseFloat(price),
        description,
        image: `IMAGES/${imageName}`,
      },
    });

    res.status(201).json(product);
  } catch (error) {
    console.error("Error:", error);
    res.status(500).json({ error: "Error creating product" });
  }
});

app.get("/api/products", async (req, res) => {
  try {
    const products = await prisma.products.findMany();
    res.json(products);
  } catch (error) {
    console.error("Error:", error);
    res.status(500).json({ error: "Error fetching products" });
  }
});

app.patch("/api/products/:id", async (req, res) => {
  const productId = parseInt(req.params.id);

  try {
    await prisma.products.update({
      where: {
        id: productId,
      },
      data: {
        isAvailable: false,
      },
    });
    res.status(204).send("Product deleted successfully");
  } catch (error) {
    console.error("Error:", error);
    res.status(500).json({ error: "Error deleting product" });
  }
});

app.listen(3000, () => {
  console.log("Server is running on port 3000");
  console.log("Visit http://localhost:3000 to view the ADMIN PANEL");
});
