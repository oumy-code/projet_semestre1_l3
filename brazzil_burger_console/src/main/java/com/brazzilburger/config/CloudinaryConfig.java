package com.brazzilburger.config;

import java.io.File;
import java.util.Map;

import com.cloudinary.Cloudinary;
import com.cloudinary.utils.ObjectUtils;

import io.github.cdimascio.dotenv.Dotenv;

public class CloudinaryConfig {
    private static Cloudinary cloudinary;
    private static Dotenv dotenv = Dotenv.configure().ignoreIfMissing().load();

    static {
        cloudinary = new Cloudinary(ObjectUtils.asMap(
            "cloud_name", dotenv.get("CLOUDINARY_CLOUD_NAME", ""),
            "api_key", dotenv.get("CLOUDINARY_API_KEY", ""),
            "api_secret", dotenv.get("CLOUDINARY_API_SECRET", ""),
            "secure", true
        ));
    }

    public static Cloudinary getInstance() {
        return cloudinary;
    }

    public static String uploadImage(String filePath, String folder) {
        try {
            File file = new File(filePath);
            if (!file.exists()) {
                throw new RuntimeException("Fichier non trouvé: " + filePath);
            }

            Map uploadResult = cloudinary.uploader().upload(file, ObjectUtils.asMap(
                "folder", "brasil-burger/" + folder,
                "resource_type", "image"
            ));

            String secureUrl = (String) uploadResult.get("secure_url");
            System.out.println("✅ Image uploadée: " + secureUrl);
            return secureUrl;

        } catch (Exception e) {
            System.err.println("❌ Erreur upload: " + e.getMessage());
            return null;
        }
    }

    public static boolean deleteImage(String publicId) {
        try {
            Map result = cloudinary.uploader().destroy(publicId, ObjectUtils.emptyMap());
            System.out.println("🗑️ Image supprimée: " + publicId);
            return "ok".equals(result.get("result"));
        } catch (Exception e) {
            System.err.println("❌ Erreur suppression: " + e.getMessage());
            return false;
        }
    }
}